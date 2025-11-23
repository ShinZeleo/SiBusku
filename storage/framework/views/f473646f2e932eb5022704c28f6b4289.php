<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket SIBUSKU #<?php echo e($booking->id); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1f2937;
        }
        .ticket {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 30px;
            background: #ffffff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #3b82f6;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            font-size: 14px;
        }
        .booking-code {
            text-align: center;
            background: #eff6ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .booking-code h2 {
            color: #1e40af;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            color: #3b82f6;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            padding: 10px;
            background: #f9fafb;
            border-radius: 6px;
        }
        .info-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        .seats {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .seats-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .seat-badge {
            background: #3b82f6;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            background: #e5e7eb;
            border: 2px dashed #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            border-radius: 8px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>SIBUSKU</h1>
            <p>E-Ticket Bus Antar Kota</p>
        </div>

        <div class="booking-code">
            <h2>#<?php echo e(str_pad($booking->id, 6, '0', STR_PAD_LEFT)); ?></h2>
            <p style="margin-top: 5px; color: #6b7280;">Kode Booking</p>
        </div>

        <div class="info-section">
            <h3>Informasi Penumpang</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Penumpang</div>
                    <div class="info-value"><?php echo e($booking->customer_name); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value"><?php echo e($booking->customer_phone); ?></div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Detail Perjalanan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Rute</div>
                    <div class="info-value">
                        <?php echo e($booking->trip->route->origin_city); ?> → <?php echo e($booking->trip->route->destination_city); ?>

                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal & Waktu</div>
                    <div class="info-value">
                        <?php echo e(\Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y')); ?>,
                        <?php echo e(\Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i')); ?> WIB
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Bus</div>
                    <div class="info-value"><?php echo e($booking->trip->bus->name); ?> (<?php echo e($booking->trip->bus->bus_class); ?>)</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Durasi</div>
                    <div class="info-value"><?php echo e($booking->trip->route->duration_estimate); ?> jam</div>
                </div>
            </div>
        </div>

        <div class="seats">
            <h3 style="color: #3b82f6; font-size: 16px; margin-bottom: 10px;">Kursi yang Dipesan</h3>
            <div class="seats-list">
                <?php $__currentLoopData = $booking->bookingSeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="seat-badge"><?php echo e($seat->seat_number); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p style="margin-top: 15px; font-size: 12px; color: #6b7280;">
                Total: <?php echo e($booking->seats_count); ?> kursi × <?php echo e($booking->trip->price_formatted); ?> =
                <strong style="color: #1e40af;"><?php echo e($booking->total_price_formatted); ?></strong>
            </p>
        </div>

        <div class="info-section">
            <h3>Status</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status Booking</div>
                    <div class="info-value" style="text-transform: uppercase;"><?php echo e($booking->status); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value" style="text-transform: uppercase;"><?php echo e($booking->payment_status); ?></div>
                </div>
            </div>
        </div>

        <div class="qr-placeholder">
            QR Code<br>
            <?php echo e($qrData); ?>

        </div>

        <div class="footer">
            <p><strong>Catatan Penting:</strong></p>
            <p>1. Tunjukkan e-ticket ini saat check-in di terminal</p>
            <p>2. Datang minimal 30 menit sebelum keberangkatan</p>
            <p>3. E-ticket ini valid untuk perjalanan yang tertera di atas</p>
            <p style="margin-top: 15px;">Dicetak pada: <?php echo e(now()->format('d M Y H:i:s')); ?></p>
        </div>
    </div>
</body>
</html>

<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/bookings/ticket-pdf.blade.php ENDPATH**/ ?>