// js/main.js
// 1) Toggle login fields by user type
const loginType = document.getElementById('login-type');
if (loginType) {
  const donorFields = document.getElementById('login-donor-fields');
  const adminFields = document.getElementById('login-admin-fields');
  const hospFields  = document.getElementById('login-hospital-fields');
  loginType.addEventListener('change', e => {
    const v = e.target.value;
    donorFields.classList.toggle('d-none', v !== 'donor');
    adminFields.classList.toggle('d-none', v !== 'admin');
    hospFields.classList.toggle('d-none', v !== 'hospital');
  });
}

// 2) Additional custom JS (future)
// e.g., client-side form validation, dynamic UI enhancements