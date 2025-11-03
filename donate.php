<?php
    include 'components/Navigation.php';
?>

<!-- Donation Page with Background -->
<div class="donation-page-wrapper">
    <!-- Donates Form -->
    <div class="container padding donation">
        <div class="heading">
            <h2>Donate To Our NGO</h2>
            <p>Help to needed !</p>
        </div>
        <div class="form_body d-flex mt-2">
            <form id="donationForm" enctype="multipart/form-data">
                <div class="form-controal d-flex">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter Your Full Name" required>
                </div>
                <div class="form-controal d-flex">
                    <label for="email">E-Mail Id</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email Id" required>
                </div>
                <div class="form-controal d-flex">
                    <label for="phone_no">Phone Number</label>
                    <input type="tel" id="phone_no" name="phone_no" placeholder="Enter Your Phone Number" required>
                </div>
                <div class="form-controal d-flex">
                    <label for="donation_amount">Donation Amount (SZL)</label>
                    <input type="number" id="donation_amount" name="donation_amount" placeholder="Enter Donation Amount" required min="1">
                </div>
                
                <!-- File Upload Section -->
                <div class="form-controal d-flex">
                    <label for="donation_proof">Upload Proof of Donation (Optional)</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="donation_proof" name="donation_proof" accept="image/*,.pdf" style="display: none;">
                        <button type="button" class="btn btn-file-select" onclick="requestFilePermission()">
                            <i class="fas fa-upload"></i> Choose File
                        </button>
                        <span id="file-name" class="file-name-display">No file chosen</span>
                        <div id="file-preview" class="file-preview"></div>
                    </div>
                    <small class="file-info">Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                </div>

                <div class="form-btn">
                    <button value="Pay Now" type="button" class="btn read-more-btn" onClick="pay_now()">
                        <i class="fas fa-hand-holding-usd"></i> Donate Now
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!--X- Donates Form -X-->
</div>

<!-- Permission Modal -->
<div id="permissionModal" class="permission-modal">
    <div class="permission-modal-content">
        <div class="permission-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3>File Access Permission</h3>
        <p>MLAPSE would like to access your device storage to upload donation proof documents.</p>
        <div class="permission-actions">
            <button class="btn-permission btn-allow" onclick="allowFileAccess()">
                <i class="fas fa-check"></i> Allow
            </button>
            <button class="btn-permission btn-deny" onclick="denyFileAccess()">
                <i class="fas fa-times"></i> Deny
            </button>
        </div>
    </div>
</div>

<!-- Actual donation Process -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let filePermissionGranted = false;
        let selectedFile = null;

        // Request file upload permission
        function requestFilePermission() {
            const modal = document.getElementById('permissionModal');
            modal.style.display = 'flex';
        }

        // Allow file access
        function allowFileAccess() {
            filePermissionGranted = true;
            const modal = document.getElementById('permissionModal');
            modal.style.display = 'none';
            
            // Show success message
            showNotification('Permission granted! You can now select files.', 'success');
            
            // Trigger file input
            document.getElementById('donation_proof').click();
        }

        // Deny file access
        function denyFileAccess() {
            filePermissionGranted = false;
            const modal = document.getElementById('permissionModal');
            modal.style.display = 'none';
            
            // Show info message
            showNotification('File upload permission denied. You can still proceed with donation.', 'info');
        }

        // Handle file selection
        document.getElementById('donation_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    showNotification('File size exceeds 5MB. Please choose a smaller file.', 'error');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    showNotification('Invalid file type. Please upload JPG, PNG, or PDF only.', 'error');
                    e.target.value = '';
                    return;
                }

                selectedFile = file;
                
                // Display file name
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-name').classList.add('file-selected');
                
                // Show preview for images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('file-preview').innerHTML = 
                            `<img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 10px;">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('file-preview').innerHTML = 
                        `<div class="pdf-preview"><i class="fas fa-file-pdf"></i> ${file.name}</div>`;
                }

                showNotification('File selected successfully!', 'success');
            }
        });

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Payment function with file upload
        function pay_now(){
            var name = jQuery('#name').val();
            var email = jQuery('#email').val();
            var phone = jQuery('#phone_no').val();
            var donation_amount = jQuery('#donation_amount').val();

            // Validate form
            if (!name || !email || !phone || !donation_amount) {
                showNotification('Please fill all required fields!', 'error');
                return;
            }

            // Create FormData to handle file upload
            var formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('donation_amount', donation_amount);
            
            if (selectedFile) {
                formData.append('donation_proof', selectedFile);
            }

            jQuery.ajax({
                type: 'post',
                url: 'payment_process.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result){
                    var options = {
                        "key": "Your RazorPay Api Key", 
                        "amount": donation_amount * 100, 
                        "currency": "ZAR",
                        "name": "MLAPSE DONATION",
                        "description": "Help To Needed",
                        "image": "assets/logo.png",
                        "handler": function (response){
                            jQuery.ajax({
                                type: 'post',
                                url: 'payment_process.php',
                                data: "payment_id=" + response.razorpay_payment_id,
                                success: function(result){
                                    window.location.href = "thank_you.php?id=" + response.razorpay_payment_id;
                                }
                            });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed', function (response){
                        showNotification('Payment failed: ' + response.error.reason, 'error');
                    });
                    rzp1.open();
                },
                error: function(xhr, status, error) {
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('permissionModal');
            if (event.target == modal) {
                denyFileAccess();
            }
        }
    </script>

    <!-- Donation Page Styles with Background -->
    <style>
        /* ===== DONATION PAGE WRAPPER WITH BACKGROUND ===== */
        .donation-page-wrapper {
           min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 48px;
    /* Background image with dark overlay for text readability */
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                url('../assets/WaveMap.jpg') center/cover no-repeat;
    text-align: center;
    position: relative;
    background-attachment: fixed; /* Parallax effect (optional) */
        }

        /* Alternative background options - uncomment one to use */
        
        /* Option 1: Use Eswatini Flag */
        /*
        .donation-page-wrapper {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('../assets/WaveMap.jpg') center/cover no-repeat;
        }
        */

        /* Option 2: Use Map of Eswatini */
        /*
        .donation-page-wrapper {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('../assets/Map.png') center/cover no-repeat;
        }
        */

        /* Option 3: Horizontal landscape image */
        
        .donation-page-wrapper {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('../assets/donation-bg.jpg') center/cover no-repeat;
        }
        

        /* Donation Section Styling */
        .donation {
            background-color: transparent;
            position: relative;
            z-index: 1;
        }

        .donation .heading h2 {
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        }

        .donation .heading p {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Form Body with Glass Effect */
        .donation .form_body {
            background: rgba(45, 45, 45, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        /* File Upload Wrapper */
        .file-upload-wrapper {
            width: 100%;
            margin-top: 8px;
        }

        .btn-file-select {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-file-select:hover {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .file-name-display {
            display: inline-block;
            margin-left: 12px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .file-name-display.file-selected {
            color: #4caf50;
            font-weight: 500;
        }

        .file-info {
            display: block;
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
        }

        .file-preview {
            margin-top: 10px;
        }

        .pdf-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 10px;
        }

        .pdf-preview i {
            font-size: 24px;
            color: #ff6b6b;
        }

        /* Permission Modal */
        .permission-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .permission-modal-content {
            background-color: #2d2d2d;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.3s ease;
        }

        .permission-icon {
            font-size: 64px;
            color: #4caf50;
            margin-bottom: 20px;
        }

        .permission-modal-content h3 {
            font-size: 24px;
            margin-bottom: 16px;
            color: #ffffff;
        }

        .permission-modal-content p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .permission-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .btn-permission {
            padding: 12px 32px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-allow {
            background-color: #4caf50;
            color: #ffffff;
        }

        .btn-allow:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .btn-deny {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-deny:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10001;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background-color: #4caf50;
            color: #ffffff;
        }

        .notification-error {
            background-color: #ff6b6b;
            color: #ffffff;
        }

        .notification-info {
            background-color: #2196F3;
            color: #ffffff;
        }

        .notification i {
            font-size: 20px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .donation-page-wrapper {
                padding: 60px 0;
                background-attachment: scroll;
            }

            .permission-modal-content {
                padding: 30px 20px;
            }

            .permission-actions {
                flex-direction: column;
            }

            .btn-permission {
                width: 100%;
                justify-content: center;
            }

            .notification {
                right: 10px;
                left: 10px;
                top: 80px;
            }

            .file-name-display {
                display: block;
                margin-left: 0;
                margin-top: 8px;
            }
        }

        @media (max-width: 480px) {
            .donation-page-wrapper {
                padding: 40px 0;
            }

            .donation .heading h2 {
                font-size: 28px;
            }
        }
    </style>

<!--X- Actual donation Process -X-->
<?php 
    include 'components/footer.php'
?>