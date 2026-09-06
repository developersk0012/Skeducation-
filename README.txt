# HiddenChat Advanced

Files:
- login.html — display-name entry
- index.html — user chat UI
- admin.html — admin inbox
- settings.html — account/settings
- config.js — Firebase configuration
- style.css — responsive dark UI

Firebase:
Project: testing-5ce76
RTDB: https://testing-5ce76-default-rtdb.firebaseio.com

Before hosting:
1. Put the correct Firebase web app config values in config.js.
2. Change ADMIN_PASSWORD.
3. Configure Realtime Database rules so users can only access their own chat and admin access is protected by a real backend/authentication system.
4. If using files, configure Firebase Storage rules.
5. Do not publish Telegram bot tokens in frontend JavaScript.

Implemented:
real-time 1-to-1 chat, reactions, reply UI, delete, copy, typing indicator, online/last-seen, attachments, links, polls, search, dark responsive UI, admin inbox.
