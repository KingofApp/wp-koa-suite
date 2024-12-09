<?php
class GooglePlayAPI {
    private $credentials;
    private $packageName;
    private $client;

    public function __construct() {
        $this->credentials = json_decode(get_option('koa_google_play_json_key'), true);
        $this->packageName = get_option('koa_google_play_package_name');
        $this->initializeClient();
    }

    private function initializeClient() {
        require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';

        $client = new Google_Client();
        $client->setAuthConfig($this->credentials);
        $client->addScope('https://www.googleapis.com/auth/androidpublisher');
        
        $this->client = $client;
    }

    public function getAnalytics($startDate, $endDate) {
        try {
            $androidPublisher = new Google_Service_AndroidPublisher($this->client);
            
            // Obtener estadísticas de la app
            $statistics = $this->getAppStatistics($androidPublisher);
            
            // Obtener datos de instalaciones
            $installs = $this->getInstallData($androidPublisher, $startDate, $endDate);
            
            // Obtener datos de usuarios activos
            $activeUsers = $this->getActiveUsers($androidPublisher, $startDate, $endDate);
            
            // Obtener datos de ingresos
            $revenue = $this->getRevenueData($androidPublisher, $startDate, $endDate);

            return [
                'installs' => $installs['total'],
                'installs_trend' => $installs['trend'],
                'active_users' => $activeUsers['total'],
                'active_users_trend' => $activeUsers['trend'],
                'sessions' => $statistics['sessions'],
                'sessions_trend' => $statistics['sessions_trend'],
                'revenue' => $revenue['total'],
                'revenue_trend' => $revenue['trend'],
                'android_phone_users' => $activeUsers['by_device']['phone'],
                'android_tablet_users' => $activeUsers['by_device']['tablet'],
                'organic_installs' => $installs['organic'],
                'non_organic_installs' => $installs['non_organic'],
                'countries' => $installs['by_country'],
                'top_events' => $this->getTopEvents($androidPublisher, $startDate, $endDate)
            ];
        } catch (Exception $e) {
            error_log('Google Play API Error: ' . $e->getMessage());
            return false;
        }
    }

    private function getAppStatistics($androidPublisher) {
        $stats = $androidPublisher->stats->get($this->packageName);
        
        return [
            'sessions' => $stats->dailyActiveUsers,
            'sessions_trend' => $this->calculateTrend($stats->dailyActiveUsers, $stats->previousDailyActiveUsers)
        ];
    }

    private function getInstallData($androidPublisher, $startDate, $endDate) {
        $installs = $androidPublisher->stats->getInstalls(
            $this->packageName,
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['country', 'deviceType', 'acquisitionChannel']
            ]
        );

        $total = 0;
        $organic = 0;
        $nonOrganic = 0;
        $byCountry = [];

        foreach ($installs->getRows() as $row) {
            $value = $row->getValue();
            $total += $value;

            // Clasificar por origen
            if ($row->getDimension('acquisitionChannel') === 'ORGANIC') {
                $organic += $value;
            } else {
                $nonOrganic += $value;
            }

            // Agrupar por país
            $country = $row->getDimension('country');
            if (!isset($byCountry[$country])) {
                $byCountry[$country] = [
                    'installs' => 0,
                    'percentage' => 0
                ];
            }
            $byCountry[$country]['installs'] += $value;
        }

        // Calcular porcentajes por país
        foreach ($byCountry as &$country) {
            $country['percentage'] = ($country['installs'] / $total) * 100;
        }

        // Ordenar países por instalaciones
        arsort($byCountry);

        return [
            'total' => $total,
            'trend' => $this->calculateTrend($total, $installs->getPreviousTotalInstalls()),
            'organic' => $organic,
            'non_organic' => $nonOrganic,
            'by_country' => array_slice($byCountry, 0, 5) // Top 5 países
        ];
    }

    private function getActiveUsers($androidPublisher, $startDate, $endDate) {
        $activeUsers = $androidPublisher->stats->getActiveUsers(
            $this->packageName,
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['deviceType']
            ]
        );

        $total = 0;
        $byDevice = [
            'phone' => 0,
            'tablet' => 0
        ];

        foreach ($activeUsers->getRows() as $row) {
            $value = $row->getValue();
            $total += $value;

            $deviceType = $row->getDimension('deviceType');
            if ($deviceType === 'PHONE') {
                $byDevice['phone'] += $value;
            } elseif ($deviceType === 'TABLET') {
                $byDevice['tablet'] += $value;
            }
        }

        return [
            'total' => $total,
            'trend' => $this->calculateTrend($total, $activeUsers->getPreviousTotalUsers()),
            'by_device' => $byDevice
        ];
    }

    private function getRevenueData($androidPublisher, $startDate, $endDate) {
        $revenue = $androidPublisher->stats->getRevenue(
            $this->packageName,
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['country', 'sku']
            ]
        );

        $total = 0;
        $byProduct = [];
        $byCountry = [];

        foreach ($revenue->getRows() as $row) {
            $value = $row->getValue();
            $total += $value;

            // Agrupar por producto
            $sku = $row->getDimension('sku');
            if (!isset($byProduct[$sku])) {
                $byProduct[$sku] = 0;
            }
            $byProduct[$sku] += $value;

            // Agrupar por país
            $country = $row->getDimension('country');
            if (!isset($byCountry[$country])) {
                $byCountry[$country] = 0;
            }
            $byCountry[$country] += $value;
        }

        // Ordenar productos y países
        arsort($byProduct);
        arsort($byCountry);

        return [
            'total' => $total,
            'trend' => $this->calculateTrend($total, $revenue->getPreviousTotalRevenue()),
            'by_product' => array_slice($byProduct, 0, 5),
            'by_country' => array_slice($byCountry, 0, 5)
        ];
    }

    private function getTopEvents($androidPublisher, $startDate, $endDate) {
        $events = $androidPublisher->stats->getEvents(
            $this->packageName,
            [
                'startDate' => $startDate,
                'endDate' => $endDate
            ]
        );

        $topEvents = [];
        foreach ($events->getRows() as $row) {
            $eventName = $row->getDimension('eventName');
            $topEvents[$eventName] = [
                'name' => $eventName,
                'total' => $row->getValue(),
                'first_time' => $row->getFirstTimeUsers()
            ];
        }

        // Ordenar eventos por total
        uasort($topEvents, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        return array_slice($topEvents, 0, 5); // Retornar top 5 eventos
    }

    private function calculateTrend($current, $previous) {
        if ($previous == 0) {
            return 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
} 