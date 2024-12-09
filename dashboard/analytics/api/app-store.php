<?php
class AppStoreAPI {
    private $keyId;
    private $issuerId;
    private $keyFile;
    private $bundleId;
    private $token;

    public function __construct() {
        $this->keyId = get_option('koa_appstore_key_id');
        $this->issuerId = get_option('koa_appstore_issuer_id');
        $this->keyFile = get_option('koa_appstore_key_file');
        $this->bundleId = get_option('koa_appstore_bundle_id');
    }

    public function getAnalytics($startDate, $endDate) {
        $token = $this->getJWTToken();
        if (!$token) {
            return false;
        }

        $metrics = $this->fetchMetrics($startDate, $endDate);
        return $this->processMetrics($metrics);
    }

    private function getJWTToken() {
        if (!$this->keyFile || !$this->keyId || !$this->issuerId) {
            return false;
        }

        $header = [
            'alg' => 'ES256',
            'kid' => $this->keyId,
            'typ' => 'JWT'
        ];

        $payload = [
            'iss' => $this->issuerId,
            'exp' => time() + 1200, // 20 minutos
            'aud' => 'appstoreconnect-v1'
        ];

        $privateKey = openssl_pkey_get_private($this->keyFile);
        if (!$privateKey) {
            return false;
        }

        $headerEncoded = base64_encode(json_encode($header));
        $payloadEncoded = base64_encode(json_encode($payload));
        $dataToSign = $headerEncoded . '.' . $payloadEncoded;

        $signature = '';
        openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $signatureEncoded = base64_encode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    private function fetchMetrics($startDate, $endDate) {
        $endpoint = 'https://api.appstoreconnect.apple.com/v1/apps/' . $this->bundleId . '/metrics';
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'filter[metrics]' => 'installations,sessions,activeDevices,crashes,uninstalls',
                'filter[startTime]' => $startDate . 'T00:00:00Z',
                'filter[endTime]' => $endDate . 'T23:59:59Z',
                'filter[dimension]' => 'platform,device,territory'
            ])
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function processMetrics($metrics) {
        if (!$metrics || !isset($metrics['data'])) {
            return [
                'installs' => 0,
                'active_users' => 0,
                'sessions' => 0,
                'revenue' => 0,
                'installs_trend' => 0,
                'active_users_trend' => 0,
                'sessions_trend' => 0,
                'revenue_trend' => 0
            ];
        }

        // Procesar datos de instalaciones
        $installations = array_filter($metrics['data'], function($metric) {
            return $metric['type'] === 'installations';
        });

        // Procesar datos de sesiones
        $sessions = array_filter($metrics['data'], function($metric) {
            return $metric['type'] === 'sessions';
        });

        // Procesar datos de dispositivos activos
        $activeDevices = array_filter($metrics['data'], function($metric) {
            return $metric['type'] === 'activeDevices';
        });

        // Calcular tendencias
        $installsTrend = $this->calculateTrend($installations);
        $sessionsTrend = $this->calculateTrend($sessions);
        $activeUsersTrend = $this->calculateTrend($activeDevices);

        return [
            'installs' => $this->sumMetrics($installations),
            'active_users' => $this->sumMetrics($activeDevices),
            'sessions' => $this->sumMetrics($sessions),
            'revenue' => $this->getRevenue($metrics),
            'installs_trend' => $installsTrend,
            'active_users_trend' => $activeUsersTrend,
            'sessions_trend' => $sessionsTrend,
            'revenue_trend' => 0, // Implementar cálculo de tendencia de ingresos
            'ios_phone_users' => $this->getDeviceTypeUsers($activeDevices, 'iPhone'),
            'ios_tablet_users' => $this->getDeviceTypeUsers($activeDevices, 'iPad')
        ];
    }

    private function calculateTrend($metrics) {
        if (empty($metrics)) {
            return 0;
        }

        $values = array_column($metrics, 'data');
        $current = end($values);
        $previous = reset($values);

        if ($previous == 0) {
            return 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function sumMetrics($metrics) {
        return array_reduce($metrics, function($carry, $metric) {
            return $carry + $metric['data'];
        }, 0);
    }

    private function getDeviceTypeUsers($metrics, $deviceType) {
        return array_reduce($metrics, function($carry, $metric) use ($deviceType) {
            if ($metric['dimensions']['device'] === $deviceType) {
                return $carry + $metric['data'];
            }
            return $carry;
        }, 0);
    }

    private function getRevenue($metrics) {
        // Obtener datos de ingresos de la API de App Store Connect
        $endpoint = 'https://api.appstoreconnect.apple.com/v1/salesReports';
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'filter[frequency]' => 'DAILY',
                'filter[reportType]' => 'SALES',
                'filter[reportSubType]' => 'SUMMARY',
                'filter[vendorNumber]' => get_option('koa_appstore_vendor_number'),
                'filter[startDate]' => date('Y-m-d', strtotime('-30 days')),
                'filter[endDate]' => date('Y-m-d')
            ])
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $salesData = json_decode($response, true);

        if (!$salesData || !isset($salesData['data'])) {
            return [
                'total' => 0,
                'by_product' => [],
                'by_country' => [],
                'trend' => 0
            ];
        }

        // Procesar datos de ventas
        $revenue = [
            'total' => 0,
            'by_product' => [],
            'by_country' => [],
            'daily' => []
        ];

        foreach ($salesData['data'] as $sale) {
            // Sumar ingresos totales
            $amount = floatval($sale['attributes']['proceeds']);
            $revenue['total'] += $amount;

            // Agrupar por producto
            $productId = $sale['attributes']['sku'];
            if (!isset($revenue['by_product'][$productId])) {
                $revenue['by_product'][$productId] = 0;
            }
            $revenue['by_product'][$productId] += $amount;

            // Agrupar por país
            $country = $sale['attributes']['territory'];
            if (!isset($revenue['by_country'][$country])) {
                $revenue['by_country'][$country] = 0;
            }
            $revenue['by_country'][$country] += $amount;

            // Agrupar por día
            $date = $sale['attributes']['date'];
            if (!isset($revenue['daily'][$date])) {
                $revenue['daily'][$date] = 0;
            }
            $revenue['daily'][$date] += $amount;
        }

        // Calcular tendencia
        $dates = array_keys($revenue['daily']);
        sort($dates);
        $firstDay = reset($dates);
        $lastDay = end($dates);

        if (isset($revenue['daily'][$firstDay]) && isset($revenue['daily'][$lastDay])) {
            $firstDayRevenue = $revenue['daily'][$firstDay];
            $lastDayRevenue = $revenue['daily'][$lastDay];

            if ($firstDayRevenue > 0) {
                $revenue['trend'] = round((($lastDayRevenue - $firstDayRevenue) / $firstDayRevenue) * 100, 2);
            }
        }

        // Ordenar productos por ingresos
        arsort($revenue['by_product']);
        $revenue['by_product'] = array_slice($revenue['by_product'], 0, 5); // Top 5 productos

        // Ordenar países por ingresos
        arsort($revenue['by_country']);
        $revenue['by_country'] = array_slice($revenue['by_country'], 0, 5); // Top 5 países

        // Obtener nombres de productos
        $productIds = array_keys($revenue['by_product']);
        if (!empty($productIds)) {
            $productsEndpoint = 'https://api.appstoreconnect.apple.com/v1/apps/' . 
                               $this->bundleId . '/inAppPurchases?filter[id]=' . 
                               implode(',', $productIds);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $productsEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: application/json'
                ]
            ]);

            $productsResponse = curl_exec($ch);
            curl_close($ch);

            $productsData = json_decode($productsResponse, true);
            if ($productsData && isset($productsData['data'])) {
                $productNames = [];
                foreach ($productsData['data'] as $product) {
                    $productNames[$product['id']] = $product['attributes']['name'];
                }

                // Reemplazar IDs por nombres
                $namedProducts = [];
                foreach ($revenue['by_product'] as $id => $amount) {
                    $name = $productNames[$id] ?? $id;
                    $namedProducts[$name] = $amount;
                }
                $revenue['by_product'] = $namedProducts;
            }
        }

        return $revenue;
    }
} 