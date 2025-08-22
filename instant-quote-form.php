<?php
/**
 * Plugin Name: Instant Quote Form
 * Description: A simple file upload + details form with popup (shortcode: [instant_quote]).
 * Version: 1.3
 * Author: Uchit Chakma
 */

if (!defined('ABSPATH')) exit;

add_action('admin_init', function() {
    register_setting('iqf_settings_group', 'iqf_to_email');
    register_setting('iqf_settings_group', 'iqf_from_email');
    register_setting('iqf_settings_group', 'iqf_upload_text', [
        'default' => 'Upload File'
    ]);
    register_setting('iqf_settings_group', 'iqf_form_title', [
    'default' => 'Instant Form'
]);
register_setting('iqf_settings_group', 'iqf_form_title_size', [
    'default' => '20px'
]);
register_setting('iqf_settings_group', 'iqf_form_title_color', [
    'default' => '#222222'
]);
register_setting('iqf_settings_group', 'iqf_form_title_weight', [
    'default' => 'bold'
]);
register_setting('iqf_settings_group', 'iqf_allowed_formats', [
    'default' => 'jpg,png,pdf'
]);
    register_setting('iqf_settings_group', 'iqf_upload_icon_img');
    register_setting('iqf_settings_group', 'iqf_btn_color', [
        'default' => '#1A3A5F'
    ]);
    register_setting('iqf_settings_group', 'iqf_btn_radius', [
        'default' => '8px'
    ]);
    register_setting('iqf_settings_group', 'iqf_btn_padding', [
        'default' => '12px 24px'
    ]);
    register_setting('iqf_settings_group', 'iqf_btn_text_color', [
    'default' => '#ffffff'
]);
});

// Add settings page to admin menu
add_action('admin_menu', function() {
    add_menu_page(
        'Instant Quote Form',
        'Instant Quote Form',
        'manage_options',
        'instant-quote-form-settings',
        'iqf_settings_page',
        'dashicons-email',
        80
    );
});

// Register settings
add_action('admin_init', function() {
    register_setting('iqf_settings_group', 'iqf_to_email');
    register_setting('iqf_settings_group', 'iqf_from_email');
    register_setting('iqf_settings_group', 'iqf_upload_text', [
        'default' => 'Upload File'
    ]);
    register_setting('iqf_settings_group', 'iqf_upload_icon_img');
});

// Settings page HTML
function iqf_settings_page() {
      wp_enqueue_media();

    $icon_img = esc_url(get_option('iqf_upload_icon_img', ''));
    ?>
    <div class="wrap uchit">
        <h1>Instant Quote Form Settings</h1>
        <p><strong>Shortcode:</strong> <code>[instant_quote]</code></p>
        <form method="post" action="options.php">
            <?php settings_fields('iqf_settings_group'); ?>
            <?php do_settings_sections('iqf_settings_group'); ?>
            <table class="form-table">
              <tr valign="top">
    <th scope="row">Form Title</th>
    <td>
        <input type="text" name="iqf_form_title" value="<?php echo esc_attr(get_option('iqf_form_title', 'Instant Form')); ?>" style="width:250px;" />
        <p class="description">Set the form title.</p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Form Title Font Size</th>
    <td>
        <input type="text" name="iqf_form_title_size" value="<?php echo esc_attr(get_option('iqf_form_title_size', '20px')); ?>" style="width:80px;" />
        <p class="description">e.g. <code>20px</code>, <code>1.5em</code></p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Form Title Color</th>
    <td>
        <input type="color" name="iqf_form_title_color" value="<?php echo esc_attr(get_option('iqf_form_title_color', '#222222')); ?>" />
        <p class="description">Pick a color for the form title.</p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Form Title Font Weight</th>
    <td>
        <select name="iqf_form_title_weight">
            <?php
            $weights = ['normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
            $selected_weight = get_option('iqf_form_title_weight', 'bold');
            foreach ($weights as $weight) {
                echo '<option value="'.$weight.'"'.($selected_weight == $weight ? ' selected' : '').'>'.$weight.'</option>';
            }
            ?>
        </select>
        <p class="description">Choose font weight for the form title.</p>
    </td>
</tr>
                <tr valign="top">
                    <th scope="row">To Email(s)</th>
                    <td>
                        <input type="text" name="iqf_to_email" value="<?php echo esc_attr(get_option('iqf_to_email', '')); ?>" style="width:350px;" />
                        <p class="description">Separate multiple emails with commas.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">From Email</th>
                    <td>
                        <input type="text" name="iqf_from_email" value="<?php echo esc_attr(get_option('iqf_from_email', '')); ?>" style="width:350px;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Upload Button Icon (Image)</th>
                    <td>
                        <input type="hidden" id="iqf_upload_icon_img" name="iqf_upload_icon_img" value="<?php echo $icon_img; ?>" />
                        <button type="button" class="button" id="iqf_upload_icon_img_btn">Select Icon</button>
                        <div id="iqf_upload_icon_img_preview" style="margin-top:10px;">
                            <?php if ($icon_img): ?>
                                <img src="<?php echo $icon_img; ?>" style="max-width:40px;max-height:40px;" />
                            <?php endif; ?>
                        </div>
                        <p class="description">Select an image from the media library (SVG, PNG, JPG). If not set, a default icon will be used.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Upload Button Text</th>
                    <td>
                        <input type="text" name="iqf_upload_text" value="<?php echo esc_attr(get_option('iqf_upload_text', 'Upload File')); ?>" style="width:150px;" />
                        <p class="description">Text for the upload button.</p>
                    </td>
                </tr>
                <tr valign="top">
    <th scope="row">Upload Button Color</th>
    <td>
        <input type="color" name="iqf_btn_color" value="<?php echo esc_attr(get_option('iqf_btn_color', '#1A3A5F')); ?>" />
        <p class="description">Pick a color for the upload button.</p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Upload Button Border Radius</th>
    <td>
        <input type="text" name="iqf_btn_radius" value="<?php echo esc_attr(get_option('iqf_btn_radius', '8px')); ?>" style="width:80px;" />
        <p class="description">e.g. <code>8px</code>, <code>50%</code></p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Upload Button Padding</th>
    <td>
        <input type="text" name="iqf_btn_padding" value="<?php echo esc_attr(get_option('iqf_btn_padding', '12px 24px')); ?>" style="width:120px;" />
        <p class="description">e.g. <code>12px 24px</code>, <code>20px</code></p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Upload Button Text Color</th>
    <td>
        <input type="color" name="iqf_btn_text_color" value="<?php echo esc_attr(get_option('iqf_btn_text_color', '#ffffff')); ?>" />
        <p class="description">Pick a color for the button text.</p>
    </td>
</tr>
<tr valign="top">
    <th scope="row">Allowed File Formats</th>
    <td>
        <input type="text" name="iqf_allowed_formats" value="<?php echo esc_attr(get_option('iqf_allowed_formats', 'jpg,png,pdf')); ?>" style="width:200px;" />
        <p class="description">Enter allowed file extensions separated by comma. Example: <code>jpg,png,pdf</code></p>
    </td>
</tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($){
        $('#iqf_upload_icon_img_btn').on('click', function(e){
            e.preventDefault();
            var frame = wp.media({
                title: 'Select or Upload Icon',
                button: { text: 'Use this icon' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#iqf_upload_icon_img').val(attachment.url);
                $('#iqf_upload_icon_img_preview').html('<img src="'+attachment.url+'" style="max-width:40px;max-height:40px;" />');
            });
            frame.open();
        });
    });
    </script>
<?php }

// Register shortcode
function iqf_shortcode() {
    $btn_text = esc_html(get_option('iqf_upload_text', 'Upload File'));
    $icon_img = esc_url(get_option('iqf_upload_icon_img', ''));
    $btn_color = esc_attr(get_option('iqf_btn_color', '#1A3A5F'));
    $btn_radius = esc_attr(get_option('iqf_btn_radius', '8px'));
    $btn_padding = esc_attr(get_option('iqf_btn_padding', '12px 24px'));
    $btn_text_color = esc_attr(get_option('iqf_btn_text_color', '#ffffff'));
    ob_start(); ?>
    
    
    <style>
    #uploadBtn {
      padding: <?php echo $btn_padding; ?>;
      background: <?php echo $btn_color; ?>;
      color: <?php echo $btn_text_color; ?>;
      border: none;
      border-radius: <?php echo $btn_radius; ?>;
      cursor: pointer;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    #uploadBtn img, #uploadBtn svg {
      display: inline-flex;
      vertical-align: middle;
      max-width: 22px;
      max-height: 22px;
    }
    #popupOverlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.5);
      z-index: 9998;
    }
    #customPopup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      padding: 24px;
      z-index: 9999;
      max-width: 400px;
      width: 100%;
    }
    #customPopup h2 {
      margin-top: 0;
      font-size: 20px;
      text-align: center;
    }
    #customPopup .check {
      font-size: 40px;
      color: green;
      text-align: center;
      margin: 10px 0;
    }
    #customPopup .filename {
      text-align: center;
      font-weight: bold;
      margin-bottom: 15px;
    }
    #customPopup input {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    #customPopup button {
      background: #4CAF50;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
    }
    #closePopupBtn {
      position: absolute;
      top: 12px;
      right: 16px;
      background: none;
      border: none;
      cursor: pointer;
      z-index: 10000;
      padding: 3px !important;
      width: 32px !important;
      height: 32px !important;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s;
      background: transparent !important;
    }
    #closePopupBtn:hover svg {
      stroke: #333;
    }
    </style>

    <!-- Upload Button -->
    <button id="uploadBtn" type="button" class="uchit">
      <?php if ($icon_img): ?>
        <img src="<?php echo $icon_img; ?>" alt="Upload Icon" />
      <?php else: ?>
        <!-- Default SVG upload icon -->
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="17 8 12 3 7 8"/>
          <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
      <?php endif; ?>
      <span><?php echo $btn_text; ?></span>
    </button>

    <!-- Hidden file input -->
<input type="file" id="hiddenFileInput" style="display:none"
  accept="<?php
    $formats = get_option('iqf_allowed_formats', 'jpg,png,pdf');
    $accept = implode(',', array_map(function($f) { return '.' . trim($f); }, explode(',', $formats)));
    echo esc_attr($accept);
  ?>"
/>
    <!-- Overlay -->
    <div id="popupOverlay"></div>

    <!-- Popup -->
<div id="customPopup" class="uchit">
  <button id="closePopupBtn" title="Close">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="#888" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="18" y1="6" x2="6" y2="18"/>
      <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
  </button>
  <h2>Instant Form</h2>
  <div style="display:flex;align-items:center;justify-content:center;margin-bottom:15px;">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" stroke="green" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:10px;">
  <polyline points="20 6 9 17 4 12"/>
</svg>    <span class="filename" id="fileNameDisplay" style="font-weight:bold;"></span>
  </div>
  <form id="quoteForm" method="post" enctype="multipart/form-data">
    <input type="file" name="uploaded_file" id="realFileInput" style="display:none" />
    <input type="email" name="business_email" placeholder="Business Email" required>
    <span class="error" id="emailError" style="color:red;font-size:12px;display:none;"></span>
    <input type="text" name="name" placeholder="Business Name" required>
    <span class="error" id="nameError" style="color:red;font-size:12px;display:none;"></span>
    <input type="text" name="company_name" placeholder="Company Name" required>
    <span class="error" id="companyError" style="color:red;font-size:12px;display:none;"></span>
    <button type="submit">Get Instant Quote</button>
  </form>
  <div id="successMsg" style="display:none;text-align:center;color:green;font-weight:bold;margin-top:15px;"></div>
</div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
  const uploadBtn = document.getElementById("uploadBtn");
  const hiddenFileInput = document.getElementById("hiddenFileInput");
  const popup = document.getElementById("customPopup");
  const overlay = document.getElementById("popupOverlay");
  const fileNameDisplay = document.getElementById("fileNameDisplay");
  const realFileInput = document.getElementById("realFileInput");
  const form = document.getElementById("quoteForm");
  const successMsg = document.getElementById("successMsg");
  const closePopupBtn = document.getElementById("closePopupBtn");
  const allowedFormats = "<?php echo esc_attr(get_option('iqf_allowed_formats', 'jpg,png,pdf')); ?>".split(',').map(f => f.trim().toLowerCase());


  uploadBtn.addEventListener("click", () => hiddenFileInput.click());

  hiddenFileInput.addEventListener("change", function () {
    if (hiddenFileInput.files.length > 0) {
      let file = hiddenFileInput.files[0];
      let ext = file.name.split('.').pop().toLowerCase();
      if (!allowedFormats.includes(ext)) {
        alert("This file type is not allowed. Allowed formats: " + allowedFormats.join(', '));
        hiddenFileInput.value = "";
        return;
      }
      fileNameDisplay.textContent = file.name;
      popup.style.display = "block";
      overlay.style.display = "block";
      realFileInput.files = hiddenFileInput.files;
      successMsg.style.display = "none";
      successMsg.textContent = "";
    }
  });

  function closePopup() {
    popup.style.display = "none";
    overlay.style.display = "none";
    form.reset();
    realFileInput.value = "";
    hiddenFileInput.value = "";
    successMsg.style.display = "none";
    successMsg.textContent = "";
    document.getElementById("emailError").style.display = "none";
    document.getElementById("nameError").style.display = "none";
    document.getElementById("companyError").style.display = "none";
  }

  overlay.addEventListener("click", closePopup);
  closePopupBtn.addEventListener("click", closePopup);

  form.addEventListener("submit", function(e) {
    e.preventDefault();

    document.getElementById("emailError").style.display = "none";
    document.getElementById("nameError").style.display = "none";
    document.getElementById("companyError").style.display = "none";

    let valid = true;

    const email = form.business_email.value.trim();
    const freeDomains = [
      "gmail.com", "yahoo.com", "hotmail.com", "outlook.com", "aol.com", "icloud.com", "mail.com", "protonmail.com", "zoho.com"
    ];
    const emailDomain = email.split("@")[1]?.toLowerCase();
    if (
      !email.match(/^[^@]+@[^@]+\.[^@]+$/) ||
      freeDomains.includes(emailDomain)
    ) {
      document.getElementById("emailError").textContent = "Please enter a valid company/business email address.";
      document.getElementById("emailError").style.display = "block";
      valid = false;
    }

    const name = form.name.value.trim();
    if (name.length < 2) {
      document.getElementById("nameError").textContent = "Business name is required.";
      document.getElementById("nameError").style.display = "block";
      valid = false;
    }

    const company = form.company_name.value.trim();
    if (company.length < 2) {
      document.getElementById("companyError").textContent = "Company name is required.";
      document.getElementById("companyError").style.display = "block";
      valid = false;
    }

    if (!valid) return;

    let formData = new FormData(form);
    formData.append("action", "iqf_submit_form");

    fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      successMsg.textContent = data;
      successMsg.style.display = "block";
      form.reset();
      realFileInput.value = "";
      hiddenFileInput.value = "";
    })
    .catch(err => {
      successMsg.textContent = "Error: " + err;
      successMsg.style.display = "block";
    });
  });
});
</script>

    <?php
    return ob_get_clean();
}
add_shortcode('instant_quote', 'iqf_shortcode');

// Handle AJAX form submission
function iqf_handle_form() {
    $upload = $_FILES['uploaded_file'];
    $upload_dir = wp_upload_dir();
    $target = $upload_dir['basedir'] . '/' . basename($upload['name']);

    // Get emails from settings
    $to = get_option('iqf_to_email', '');
    $from = get_option('iqf_from_email', '');

    // Support multiple recipients
    $to = array_map('trim', explode(',', $to));

    $subject = 'New Instant Quote Submission';
    $message = 'Business Email: ' . sanitize_email($_POST['business_email']) . "\n";
    $message .= 'Business Name: ' . sanitize_text_field($_POST['name']) . "\n";
    $message .= 'Company Name: ' . sanitize_text_field($_POST['company_name']) . "\n";
    $headers = array('From: Your Company <' . $from . '>');

    if (move_uploaded_file($upload['tmp_name'], $target)) {
        wp_mail($to, $subject, $message, $headers, array($target));
        echo "Form submitted successfully! File saved: " . $upload['name'];
    } else {
        echo "File upload failed.";
    }
    wp_die();
}
add_action('wp_ajax_iqf_submit_form', 'iqf_handle_form');
add_action('wp_ajax_nopriv_iqf_submit_form', 'iqf_handle_form');