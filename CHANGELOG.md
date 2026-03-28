# Change Logs

Tracking changes against upstream: [alextselegidis/easyappointments](https://github.com/alextselegidis/easyappointments)

---

### 2026-03-28 | `a762e8cd` | Fix changelog links to use commit URLs and upstream blob links
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/a762e8cdd9e90091dd9647b27c0d144765a9486b)

- `.github/scripts/generate-changelog-entry.sh` lines:[15, 18, 36, 41] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/scripts/generate-changelog-entry.sh)]
- `CHANGELOG.md` lines:[8, 10-14, 19, 21, 26, 28, 33, 35, 40, 42-43, 48, 50, 55, 57-59, 64-156, 161, 163-170, 175, 177-183, 188, 190-195, 200, 202, 207, 209, 214, 216, 221, 223-228, 233, 235-240, 245, 252, 254, 259, 261, 266, 268-269, 274, 276] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/CHANGELOG.md)]

---


### 2026-03-28 | `eeefb506` | Add auto-updating Change Logs with GitHub Action
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/eeefb5066367d2134f023f739ace17987cb4e0de)

- `.github/scripts/generate-changelog-entry.sh` lines:[1-73] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/scripts/generate-changelog-entry.sh)]
- `.github/workflows/changelog.yml` lines:[1-57] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/changelog.yml)]
- `.github/workflows/deploy.yml` lines:[35] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]
- `.gitignore` lines:[14] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.gitignore)]
- `CHANGELOG.md` lines:[1-205, 207, 209-210, 212-217, 219, 221-222, 224-229, 231, 233-234, 236, 238, 240-241, 243, 245, 247-248, 250, 252, 254-255, 257-258, 260, 262-263, 265-266, 268] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/CHANGELOG.md)]

---

### 2026-03-27 | `bd9ada01` | Add rtl.min.css for production use
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/bd9ada016efafd134f1277d8fd3abb0a1e5fe640)

- `assets/css/rtl.min.css` lines:[1-51] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/rtl.min.css)]

---

### 2026-03-27 | `0f379faf` | Fix storage permissions after deploy
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/0f379fafa1306ff38e64a58f40e38c64155316ab)

- `.github/workflows/deploy.yml` lines:[51, 55] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]

---

### 2026-03-27 | `4464f324` | Fix config.php exclude to only match root, split storage sync to preserve runtime data
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/4464f3249dd355402e672e42bf2b332f999e495c)

- `.github/workflows/deploy.yml` lines:[30-31, 38-47] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]

---

### 2026-03-27 | `f658d34a` | Fix language always using configured value, fix storage deploy rules
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/f658d34a76797c6362875045bb68cab6051d429f)

- `.github/workflows/deploy.yml` lines:[31-34] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]
- `application/config/config.php` lines:[134] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/config/config.php)]

---

### 2026-03-27 | `7bb2e38a` | Restart Apache after deploy
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/7bb2e38ac584d2357e46e07840c8ec4e41cab776)

- `.github/workflows/deploy.yml` lines:[40-45] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]

---

### 2026-03-27 | `bd7f782c` | Exclude storage/ from deploy, add missing minified files
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/bd7f782c8ed1ab10999d2b333801f999f3897b10)

- `.github/workflows/deploy.yml` lines:[31] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]
- `assets/css/general.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/general.min.css)]
- `assets/js/app.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/app.min.js)]

---

### 2026-03-27 | `6cd94a5a` | Include minified JS/CSS files — required for deployment
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/6cd94a5ac42e546e6e2dd71e13f6eab4e08113a9)

- `.gitignore` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.gitignore)]
- `assets/css/components/color_selection.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/components/color_selection.min.css)]
- `assets/css/layouts/account_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/layouts/account_layout.min.css)]
- `assets/css/layouts/backend_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/layouts/backend_layout.min.css)]
- `assets/css/layouts/booking_layout.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/layouts/booking_layout.min.css)]
- `assets/css/layouts/message_layout.min.css` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/layouts/message_layout.min.css)]
- `assets/css/pages/installation.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/pages/installation.min.css)]
- `assets/css/pages/update.min.css` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/pages/update.min.css)]
- `assets/css/themes/cosmo.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/cosmo.min.css)]
- `assets/css/themes/darkly.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/darkly.min.css)]
- `assets/css/themes/default.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/default.min.css)]
- `assets/css/themes/flatly.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/flatly.min.css)]
- `assets/css/themes/litera.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/litera.min.css)]
- `assets/css/themes/lumen.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/lumen.min.css)]
- `assets/css/themes/materia.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/materia.min.css)]
- `assets/css/themes/minty.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/minty.min.css)]
- `assets/css/themes/sketchy.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/sketchy.min.css)]
- `assets/css/themes/zephyr.min.css` lines:[1-5] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/themes/zephyr.min.css)]
- `assets/js/components/appointment_status_options.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/appointment_status_options.min.js)]
- `assets/js/components/appointments_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/appointments_modal.min.js)]
- `assets/js/components/color_selection.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/color_selection.min.js)]
- `assets/js/components/ldap_import_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/ldap_import_modal.min.js)]
- `assets/js/components/unavailabilities_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/unavailabilities_modal.min.js)]
- `assets/js/components/working_plan_exceptions_modal.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/components/working_plan_exceptions_modal.min.js)]
- `assets/js/http/account_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/account_http_client.min.js)]
- `assets/js/http/admins_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/admins_http_client.min.js)]
- `assets/js/http/api_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/api_settings_http_client.min.js)]
- `assets/js/http/appointments_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/appointments_http_client.min.js)]
- `assets/js/http/blocked_periods_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/blocked_periods_http_client.min.js)]
- `assets/js/http/booking_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/booking_http_client.min.js)]
- `assets/js/http/booking_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/booking_settings_http_client.min.js)]
- `assets/js/http/business_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/business_settings_http_client.min.js)]
- `assets/js/http/caldav_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/caldav_http_client.min.js)]
- `assets/js/http/calendar_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/calendar_http_client.min.js)]
- `assets/js/http/customers_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/customers_http_client.min.js)]
- `assets/js/http/general_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/general_settings_http_client.min.js)]
- `assets/js/http/google_analytics_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/google_analytics_settings_http_client.min.js)]
- `assets/js/http/google_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/google_http_client.min.js)]
- `assets/js/http/ldap_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/ldap_settings_http_client.min.js)]
- `assets/js/http/legal_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/legal_settings_http_client.min.js)]
- `assets/js/http/localization_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/localization_http_client.min.js)]
- `assets/js/http/login_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/login_http_client.min.js)]
- `assets/js/http/matomo_analytics_settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/matomo_analytics_settings_http_client.min.js)]
- `assets/js/http/providers_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/providers_http_client.min.js)]
- `assets/js/http/recovery_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/recovery_http_client.min.js)]
- `assets/js/http/secretaries_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/secretaries_http_client.min.js)]
- `assets/js/http/service_categories_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/service_categories_http_client.min.js)]
- `assets/js/http/services_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/services_http_client.min.js)]
- `assets/js/http/settings_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/settings_http_client.min.js)]
- `assets/js/http/unavailabilities_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/unavailabilities_http_client.min.js)]
- `assets/js/http/webhooks_http_client.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/http/webhooks_http_client.min.js)]
- `assets/js/layouts/account_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/layouts/account_layout.min.js)]
- `assets/js/layouts/backend_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/layouts/backend_layout.min.js)]
- `assets/js/layouts/booking_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/layouts/booking_layout.min.js)]
- `assets/js/layouts/message_layout.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/layouts/message_layout.min.js)]
- `assets/js/pages/account.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/account.min.js)]
- `assets/js/pages/admins.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/admins.min.js)]
- `assets/js/pages/api_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/api_settings.min.js)]
- `assets/js/pages/blocked_periods.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/blocked_periods.min.js)]
- `assets/js/pages/booking.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/booking.min.js)]
- `assets/js/pages/booking_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/booking_settings.min.js)]
- `assets/js/pages/business_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/business_settings.min.js)]
- `assets/js/pages/calendar.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/calendar.min.js)]
- `assets/js/pages/customers.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/customers.min.js)]
- `assets/js/pages/general_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/general_settings.min.js)]
- `assets/js/pages/google_analytics_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/google_analytics_settings.min.js)]
- `assets/js/pages/installation.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/installation.min.js)]
- `assets/js/pages/ldap_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/ldap_settings.min.js)]
- `assets/js/pages/legal_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/legal_settings.min.js)]
- `assets/js/pages/login.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/login.min.js)]
- `assets/js/pages/matomo_analytics_settings.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/matomo_analytics_settings.min.js)]
- `assets/js/pages/providers.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/providers.min.js)]
- `assets/js/pages/recovery.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/recovery.min.js)]
- `assets/js/pages/secretaries.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/secretaries.min.js)]
- `assets/js/pages/service_categories.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/service_categories.min.js)]
- `assets/js/pages/services.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/services.min.js)]
- `assets/js/pages/webhooks.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/pages/webhooks.min.js)]
- `assets/js/utils/calendar_default_view.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/calendar_default_view.min.js)]
- `assets/js/utils/calendar_event_popover.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/calendar_event_popover.min.js)]
- `assets/js/utils/calendar_sync.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/calendar_sync.min.js)]
- `assets/js/utils/calendar_table_view.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/calendar_table_view.min.js)]
- `assets/js/utils/date.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/date.min.js)]
- `assets/js/utils/file.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/file.min.js)]
- `assets/js/utils/http.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/http.min.js)]
- `assets/js/utils/lang.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/lang.min.js)]
- `assets/js/utils/message.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/message.min.js)]
- `assets/js/utils/string.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/string.min.js)]
- `assets/js/utils/ui.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/ui.min.js)]
- `assets/js/utils/url.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/url.min.js)]
- `assets/js/utils/validation.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/validation.min.js)]
- `assets/js/utils/working_plan.min.js` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/js/utils/working_plan.min.js)]

---

### 2026-03-27 | `2134ce89` | Move is_rtl to config variable, remove helper function
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/2134ce89df4fbdab048df569baafcbc2c4fdf57d)

- `application/config/config.php` lines:[144-145] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/config/config.php)]
- `application/helpers/language_helper.php` _(lines removed only)_ [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/helpers/language_helper.php)]
- `application/views/layouts/account_layout.php` lines:[2, 22, 28] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/account_layout.php)]
- `application/views/layouts/backend_layout.php` lines:[2, 28, 34] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/backend_layout.php)]
- `application/views/layouts/booking_layout.php` lines:[2, 32, 39] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/booking_layout.php)]
- `application/views/layouts/message_layout.php` lines:[2, 24, 30] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/message_layout.php)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/installation.php)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/update.php)]

---

### 2026-03-27 | `4cd9f1e0` | Extract is_rtl() helper to replace repeated RTL condition
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/4cd9f1e00f7cbe07b3512d82437c5963be06b930)

- `application/helpers/language_helper.php` lines:[40-51] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/helpers/language_helper.php)]
- `application/views/layouts/account_layout.php` lines:[2, 22, 28] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/account_layout.php)]
- `application/views/layouts/backend_layout.php` lines:[2, 28, 34] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/backend_layout.php)]
- `application/views/layouts/booking_layout.php` lines:[2, 32, 39] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/booking_layout.php)]
- `application/views/layouts/message_layout.php` lines:[2, 24, 30] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/message_layout.php)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/installation.php)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/update.php)]

---

### 2026-03-27 | `497e8f06` | RTL: use CSS class approach instead of inline dir attributes
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/497e8f06f9b2c4e5bf8f5a1025dafe3bd75dc300)

- `application/views/components/backend_header.php` lines:[10, 55, 73, 96] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/components/backend_header.php)]
- `application/views/layouts/account_layout.php` lines:[22-25, 28] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/account_layout.php)]
- `application/views/layouts/backend_layout.php` lines:[28-31, 34] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/backend_layout.php)]
- `application/views/layouts/booking_layout.php` lines:[32-35, 39] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/booking_layout.php)]
- `application/views/layouts/message_layout.php` lines:[24-27, 30] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/message_layout.php)]
- `assets/css/rtl.css` lines:[1-51] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/assets/css/rtl.css)]

---

### 2026-03-27 | `d1f59c41` | Add RTL to navbar dropdown menus
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/d1f59c418da115d8cdb3f7ea265297386daef56b)

- `application/views/components/backend_header.php` lines:[8, 56, 74, 97] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/components/backend_header.php)]

---

### 2026-03-27 | `ff8f389c` | Keep navbar LTR layout in RTL mode
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/ff8f389caec270cc7ad00ea1f421ccb0706cb6dc)

- `application/views/components/backend_header.php` lines:[10, 22] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/components/backend_header.php)]

---

### 2026-03-27 | `93a36465` | Fix navbar direction in RTL mode
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/93a36465bfb6bf04caec9daa1e4e5c9d300145a2)

- `application/views/components/backend_header.php` lines:[22] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/components/backend_header.php)]

---

### 2026-03-27 | `74f777ab` | Add RTL support for Hebrew, Arabic, and Persian
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/74f777ab50328221ea10bc2467da5675ad1dc142)

- `application/views/layouts/account_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/account_layout.php)]
- `application/views/layouts/backend_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/backend_layout.php)]
- `application/views/layouts/booking_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/booking_layout.php)]
- `application/views/layouts/message_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/message_layout.php)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/installation.php)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/update.php)]

---

### 2026-03-27 | `2e94ee03` | Revert RTL changes — breaks CSS loading
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/2e94ee03c873af77f943bbba5b93327c0867cd58)

- `application/views/layouts/account_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/account_layout.php)]
- `application/views/layouts/backend_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/backend_layout.php)]
- `application/views/layouts/booking_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/booking_layout.php)]
- `application/views/layouts/message_layout.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/layouts/message_layout.php)]
- `application/views/pages/installation.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/installation.php)]
- `application/views/pages/update.php` lines:[2] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/application/views/pages/update.php)]

---

### 2026-03-27 | `207f0d05` | Trigger deploy after fixing VM permissions
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/207f0d0590e13fdb2b8a03f4b2c01073dc9ba3fc)

_No file changes_

---

### 2026-03-27 | `82e560ae` | Fix rsync: skip permissions and timestamps on deploy
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/82e560aed0e0a4d3b7b28eb9a4bb8f2834943d7f)

- `.github/workflows/deploy.yml` lines:[29] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]

---

### 2026-03-27 | `2ce56377` | Fix rsync permission errors in deploy workflow
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/2ce563773831c2c97b999b6307d07b6dd416b207)

- `.github/workflows/deploy.yml` lines:[29] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]

---

### 2026-03-27 | `7d002fec` | Add GitHub Actions deploy workflow for GCP VM
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/7d002fec02a6f781fecc628976e6d54fac7ab836)

- `.github/workflows/deploy.yml` lines:[1-38] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.github/workflows/deploy.yml)]
- `deploy-targets.txt` lines:[1] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/deploy-targets.txt)]

---

### 2026-03-27 | `da81e555` | Remove CLAUDE.md from repo and add to .gitignore
[View Commit](https://github.com/HaiAtoon/easyappointments/commit/da81e55569041761fb154f321048e6524e5e7be8)

- `.gitignore` lines:[15] [[Watch](https://github.com/alextselegidis/easyappointments/blob/main/.gitignore)]
- `CLAUDE.md` _(deleted)_

---
