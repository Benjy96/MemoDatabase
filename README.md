# MemoDatabase
There are multiple users stored in userList.xml. The login page checks against this when logging in. Each user is allocated a section within memos.xml which is parsed/added to on the index page.

You can create memos, cycle through them, and search for them on the search bar on the left. You can type the ID into the search bar, or the author (user who created it – sender), the recipient, or title of the memo, and an AJAX search result will be shown.

The create memo form is validated client side, with some server-side processing for the optional URL.

