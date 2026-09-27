@extends('layout.main')
@section('title') {{ 'Delivery Verification' }} @endsection

@section('content')
<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
<div class="page-content">
    <div class="container-fluid">
        <div class="card delivery-simple-card border-0 shadow-sm mx-auto">
            <div class="card-body p-4 p-lg-5">
                <div class="delivery-simple-header text-center mb-4">
                    <div class="delivery-mark mb-3">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <h2 class="mb-2">Delivery Verification</h2>
                    <p class="text-muted mb-0">
                        Scan the receipt barcode or enter the code manually.
                    </p>
                </div>

                <div id="deliveryResult" class="alert d-none mb-4" role="alert"></div>

                <div class="delivery-input-row">
                    <div class="input-group input-group-lg delivery-input-group flex-nowrap delivery-code-field">
                        <input type="text" id="delivery_code" class="form-control" placeholder="Scan or enter code" autofocus>
                    </div>
                    <button type="button" id="startCameraScan" class="btn btn-outline-primary btn-lg camera-btn" title="Use camera">
                        <i class="fas fa-camera"></i>
                    </button>
                    <button type="button" id="verifyDeliveryBtn" class="btn btn-primary btn-lg verify-btn">
                        <i class="fas fa-check mr-2"></i> Verify
                    </button>
                </div>

                <div class="camera-preview-shell mt-3 d-none" id="cameraSection">
                    <video id="barcodeCameraPreview" class="camera-preview-video" autoplay playsinline muted></video>
                    <button type="button" id="stopCameraScan" class="btn btn-sm btn-light camera-stop-btn">
                        Stop Camera
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
<style>
    .delivery-simple-card {
        max-width: 560px;
        margin: 0 auto;
        border-radius: 18px;
        margin-top: 1rem;
    }

    .delivery-simple-header h2 {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .delivery-mark {
        width: 34px;
        height: 34px;
        margin: 0 auto;
        display: grid;
        place-items: center;
        color: #3b82f6;
        font-size: 1.9rem;
        line-height: 1;
    }

    .delivery-input-group .input-group-text {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
        border-top-left-radius: 14px;
        border-bottom-left-radius: 14px;
    }

    .delivery-input-group .form-control {
        border-top-right-radius: 14px;
        border-bottom-right-radius: 14px;
        border-color: #bfdbfe;
        box-shadow: none;
        min-height: 54px;
        font-size: 1rem;
    }

    .delivery-input-row {
        display: flex;
        gap: 0.6rem;
        align-items: stretch;
    }

    .delivery-code-field {
        flex: 1 1 0;
        min-width: 0;
    }

    .delivery-input-row .delivery-input-group {
        flex: 1 1 0;
        min-width: 0;
    }

    .verify-btn {
        min-width: 126px;
        white-space: nowrap;
    }

    .camera-btn {
        width: 54px;
        min-width: 54px;
        display: inline-grid;
        place-items: center;
        padding-left: 0;
        padding-right: 0;
    }

    .camera-preview-shell {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #0b1220;
        border: 1px solid #cbd5e1;
    }

    .camera-preview-video {
        display: block;
        width: 100%;
        min-height: 260px;
        background: #0b1220;
        object-fit: cover;
    }

    .camera-stop-btn {
        position: absolute;
        right: 0.75rem;
        bottom: 0.75rem;
        z-index: 2;
    }

    #deliveryResult {
        border-radius: 16px;
        border-width: 1px;
    }

    #deliveryResult.alert-success {
        background: #ecfdf5;
        border-color: #86efac;
        color: #166534;
    }

    #deliveryResult.alert-warning {
        background: #fffbeb;
        border-color: #fcd34d;
        color: #92400e;
    }

    #deliveryResult.alert-danger {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
    }

    @media (max-width: 991.98px) {
        .delivery-simple-card {
            margin: 0;
        }

        .delivery-simple-card {
            max-width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .delivery-simple-card .card-body {
            padding: 1rem !important;
        }

        .delivery-input-row {
            flex-direction: column;
        }

        .verify-btn,
        .camera-btn {
            width: 100%;
            min-width: 100%;
        }

        .camera-btn {
            height: 44px;
        }
    }
</style>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/zxing/zxing-library-0.21.3.min.js') }}"></script>
    <script>
        $(function() {
            const verifyUrl = '{{ route('sales.verify-delivery') }}';
            const token = '{{ csrf_token() }}';
            let cameraStream = null;
            let detector = null;
            let scanRafId = null;
            let zxingReader = null;
            let zxingControls = null;
            let scanState = { inFlight: false, lastCode: '', lastAt: 0, cooldownMs: 1500 };

            function notify(message, type) {
                if (typeof Notiflix !== 'undefined') {
                    const notifyFn = type === 'success' ? Notiflix.Notify.success : type === 'warning' ? Notiflix.Notify.warning : Notiflix.Notify.failure;
                    notifyFn(message);
                    return;
                }

                alert(message);
            }

            function normalizeCode(code) {
                return $.trim((code || '')).toUpperCase();
            }

            function canSubmit(code) {
                const now = Date.now();
                if (!code || scanState.inFlight) {
                    return false;
                }

                if (scanState.lastCode === code && (now - scanState.lastAt) < scanState.cooldownMs) {
                    return false;
                }

                scanState.inFlight = true;
                return true;
            }

            function finishSubmit(code) {
                scanState.inFlight = false;
                scanState.lastCode = code;
                scanState.lastAt = Date.now();
            }

            function showResult(message, type) {
                const $box = $('#deliveryResult');
                $box.removeClass('d-none alert-success alert-danger alert-warning').addClass('alert-' + type).text(message);
            }

            function submitCode(rawCode) {
                const code = normalizeCode(rawCode);
                if (!canSubmit(code)) {
                    return;
                }

                $.ajax({
                    url: verifyUrl,
                    type: 'POST',
                    data: {
                        _token: token,
                        delivery_code: code
                    },
                    success: function(response) {
                        finishSubmit(code);
                        $('#delivery_code').val('').focus();
                        showResult(response.message || 'Delivery verified.', 'success');
                        notify(response.message || 'Delivery verified.', 'success');
                    },
                    error: function(xhr) {
                        finishSubmit(code);
                        const message = xhr.responseJSON?.message || 'Unable to verify delivery.';
                        const type = xhr.status === 409 ? 'warning' : 'danger';
                        showResult(message, type);
                        notify(message, type === 'warning' ? 'warning' : 'error');
                        $('#delivery_code').focus().select();
                    }
                });
            }

            function stopCameraScan() {
                if (scanRafId) {
                    cancelAnimationFrame(scanRafId);
                    scanRafId = null;
                }

                if (zxingControls && typeof zxingControls.stop === 'function') {
                    zxingControls.stop();
                }
                zxingControls = null;

                if (zxingReader && typeof zxingReader.reset === 'function') {
                    zxingReader.reset();
                }
                zxingReader = null;

                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }

                const video = document.getElementById('barcodeCameraPreview');
                if (video) {
                    video.pause();
                    video.srcObject = null;
                }

                $('#cameraSection').addClass('d-none');
                $('#startCameraScan').show();
            }

            function onBarcodeScanned(codeText) {
                const cleanCode = normalizeCode(codeText);
                if (!cleanCode) {
                    return;
                }

                $('#delivery_code').val(cleanCode).focus().select();
                stopCameraScan();
                submitCode(cleanCode);
            }

            function startNativeDetector(videoEl) {
                if (!detector) {
                    detector = new BarcodeDetector({ formats: ['code_128', 'ean_13', 'ean_8', 'upc_a', 'upc_e', 'code_39', 'qr_code'] });
                }

                const detectFrame = function() {
                    if (!cameraStream) {
                        return;
                    }

                    detector.detect(videoEl)
                        .then(function(codes) {
                            if (codes && codes.length > 0) {
                                onBarcodeScanned(codes[0].rawValue);
                                return;
                            }

                            scanRafId = requestAnimationFrame(detectFrame);
                        })
                        .catch(function() {
                            scanRafId = requestAnimationFrame(detectFrame);
                        });
                };

                detectFrame();
            }

            function startZxingFallback(videoEl) {
                if (!window.ZXing || !window.ZXing.BrowserMultiFormatReader) {
                    notify('Camera scanner fallback is not available in this browser.', 'error');
                    stopCameraScan();
                    return;
                }

                zxingReader = new window.ZXing.BrowserMultiFormatReader();
                zxingReader.decodeFromVideoDevice(null, videoEl, function(result, err, controls) {
                    if (controls) {
                        zxingControls = controls;
                    }

                    if (result) {
                        onBarcodeScanned(result.getText());
                    }
                });
            }

            async function startCameraScan() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    notify('Camera is not supported on this device/browser.', 'error');
                    return;
                }

                if (!(window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
                    notify('Camera scanning requires HTTPS in production.', 'warning');
                    return;
                }

                stopCameraScan();

                try {
                    cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false });

                    const video = document.getElementById('barcodeCameraPreview');
                    video.srcObject = cameraStream;
                    await video.play();

                    $('#cameraSection').removeClass('d-none');
                    $('#startCameraScan').hide();

                    if ('BarcodeDetector' in window) {
                        startNativeDetector(video);
                    } else {
                        startZxingFallback(video);
                    }
                } catch (error) {
                    notify('Unable to start camera. Please allow camera permission and retry.', 'error');
                    stopCameraScan();
                }
            }

            $(document).on('click', '#startCameraScan', function() {
                startCameraScan();
            });

            $(document).on('click', '#stopCameraScan', function() {
                stopCameraScan();
            });

            $(document).on('click', '#verifyDeliveryBtn', function() {
                submitCode($('#delivery_code').val());
            });

            $(document).on('keydown', '#delivery_code', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitCode($(this).val());
                }
            });

            $(window).on('beforeunload', function() {
                stopCameraScan();
            });

            $('#delivery_code').on('input', function() {
                $('#deliveryResult').addClass('d-none').removeClass('alert-success alert-warning alert-danger').text('');
            });
        });
    </script>
@endsection