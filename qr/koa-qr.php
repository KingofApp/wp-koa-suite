<?php
if (!defined('ABSPATH')) exit;
?>

<div class="distribution-container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">QR Code Generator</h5>
                </div>
                <div class="card-body">
                    <!-- Generador de QR -->
                    <form id="qr-generator-form">
                        <div class="mb-3">
                            <label for="qr-url" class="form-label">URL de la App</label>
                            <input type="url" class="form-control" id="qr-url" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Generar QR</button>
                    </form>
                    <div id="qr-result" class="mt-3 text-center"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Enlaces de Distribución</h5>
                </div>
                <div class="card-body">
                    <!-- Enlaces de distribución -->
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fab fa-google-play me-2"></i>Google Play Store
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fab fa-apple me-2"></i>App Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>