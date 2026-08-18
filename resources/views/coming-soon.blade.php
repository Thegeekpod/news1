<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $g_settings['coming_soon_title'] ?? 'শীঘ্রই আসছি' }} – {{ $g_settings['site_name'] ?? 'নিউজ১' }}</title>
  
  @if(!empty($g_settings['site_favicon']))
    <link rel="shortcut icon" href="{{ asset($g_settings['site_favicon']) }}" type="image/x-icon">
    <link rel="icon" href="{{ asset($g_settings['site_favicon']) }}" type="image/x-icon">
  @endif

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <style>
    body {
      background: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      margin: 0;
      padding: 20px;
    }
    
    .coming-soon-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 48px 40px;
      max-width: 580px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.08), 0 0 1px rgba(0, 0, 0, 0.04);
    }
    
    .logo {
      margin-bottom: 28px;
      display: inline-block;
      text-decoration: none;
    }
    
    .logo img {
      max-height: 65px;
      object-fit: contain;
    }
    
    h1 {
      font-size: 26px;
      font-weight: 800;
      margin-bottom: 14px;
      color: #0f172a;
      letter-spacing: -0.02em;
    }
    
    p {
      color: #64748b;
      font-size: 15px;
      line-height: 1.65;
      margin-bottom: 32px;
    }
    
    .subscribe-form {
      display: flex;
      gap: 10px;
      margin-bottom: 24px;
      max-width: 480px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .subscribe-input {
      flex: 1;
      padding: 13px 18px;
      border-radius: 8px;
      border: 1.5px solid #cbd5e1;
      background: #f8fafc;
      color: #0f172a;
      font-size: 14px;
      outline: none;
      transition: all 0.2s ease;
    }
    
    .subscribe-input::placeholder {
      color: #94a3b8;
    }
    
    .subscribe-input:focus {
      background: #ffffff;
      border-color: #e8101a;
      box-shadow: 0 0 0 3px rgba(232, 16, 26, 0.1);
    }
    
    .subscribe-btn {
      padding: 13px 26px;
      border-radius: 8px;
      border: none;
      background: #e8101a;
      color: #ffffff;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    
    .subscribe-btn:hover {
      background: #c10d15;
      transform: translateY(-1px);
    }
    
    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #64748b;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s;
    }
    
    .btn-home:hover {
      color: #e8101a;
    }
    
    .msg-box {
      margin-top: 10px;
      font-size: 13px;
      font-weight: 600;
    }
  </style>
</head>

<body>

  <div class="coming-soon-card">
    <div class="logo">
      <img src="{{ asset('logo.jpeg') }}" alt="{{ $g_settings['site_name'] ?? 'NEWS 1' }} Logo">
    </div>
    
    <h1>{{ $g_settings['coming_soon_title'] ?? 'আমাদের ওয়েবসাইট খুব শীঘ্রই চালু হচ্ছে!' }}</h1>
    <p>{{ $g_settings['coming_soon_description'] ?? 'আমাদের ওয়েবসাইট প্রস্তুত করার কাজ চলছে। সর্বশেষ আপডেট পেতে এবং ওয়েবসাইট চালু হওয়ার সাথে সাথে জানতে আপনার ইমেল দিয়ে সাবস্ক্রাইব করুন।' }}</p>
    
    <div class="subscribe-form">
      <input type="email" id="cs-email" placeholder="আপনার ইমেইল ঠিকানা" class="subscribe-input">
      <button id="cs-submit" class="subscribe-btn">সাবস্ক্রাইব</button>
    </div>
    <div class="msg-box" id="cs-msg" style="display: none;"></div>
    
    <div style="margin-top: 20px;">
      <div style="font-size: 13px; color: #64748b;">
        &copy; {{ date('Y') }} {{ $g_settings['site_name'] ?? 'নিউজ১' }}। সর্বস্বত্ব সংরক্ষিত।
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // AJAX Subscribe form
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const submitBtn = document.getElementById('cs-submit');
      const emailInput = document.getElementById('cs-email');
      const msgBox = document.getElementById('cs-msg');

      if (submitBtn && emailInput) {
        submitBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const email = emailInput.value.trim();
          if (!email) {
            emailInput.style.borderColor = '#e8101a';
            setTimeout(() => emailInput.style.borderColor = '', 2000);
            return;
          }

          submitBtn.disabled = true;
          submitBtn.textContent = 'অপেক্ষা করুন...';

          fetch("{{ route('newsletter.subscribe') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email: email })
          })
          .then(res => res.json())
          .then(data => {
            msgBox.style.display = 'block';
            msgBox.textContent = data.message;
            if (data.success) {
              msgBox.style.color = '#10b981';
              emailInput.value = '';
              submitBtn.textContent = 'সফল!';
              submitBtn.style.background = '#10b981';
              setTimeout(() => {
                msgBox.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.textContent = 'সাবস্ক্রাইব';
                submitBtn.style.background = '';
              }, 3000);
            } else {
              msgBox.style.color = '#ef4444';
              submitBtn.disabled = false;
              submitBtn.textContent = 'সাবস্ক্রাইব';
            }
          })
          .catch(err => {
            msgBox.style.display = 'block';
            msgBox.style.color = '#ef4444';
            msgBox.textContent = 'সার্ভারে সমস্যা হয়েছে, আবার চেষ্টা করুন।';
            submitBtn.disabled = false;
            submitBtn.textContent = 'সাবস্ক্রাইব';
          });
        });
      }
    });
  </script>

</body>

</html>
