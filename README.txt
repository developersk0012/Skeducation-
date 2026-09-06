VEILCHAT — NEW STANDALONE TESTING BUILD

This is a NEW website, not a copy of the SK Education UI/database.

Firebase testing project:
- projectId: testing-5ce76
- databaseURL: https://testing-5ce76-default-rtdb.firebaseio.com
- storageBucket: testing-5ce76.firebasestorage.app

Database namespace used by this app:
- veil_users
- veil_chats
- veil_files (Firebase Storage)

Pages:
- login.html
- index.html
- settings.html

Advanced features:
- Real-time 1-to-1 chat
- Online/last-seen
- Typing indicator
- Reply
- Reactions
- Delete own message
- Copy
- Message search
- Photo/video/document upload
- Link sharing
- Polls
- Read ticks
- Responsive mobile UI
- Separate testing namespace

IMPORTANT:
Firebase Realtime Database and Storage rules must permit the operations you want.
For a real production deployment, use Firebase Authentication and proper security rules rather than client-side identity alone.
No Telegram bot token is embedded in this frontend.
