# MemoDatabase
There are multiple users stored in userList.xml. The login page checks against this when logging in. Each user is allocated a section within memos.xml which is parsed/added to on the index page.

You can create memos, cycle through them, and search for them on the search bar on the left. For example, you can type the ID into the search bar, 
the author (user who created it – sender), the recipient, the title of the memo, the date, etc., and an AJAX search result will be shown.

The create memo form ensures all required inputs have been entered (client-side validation with bootstrap), with some server-side PHP validation checking the 
optional URL (if entered) is a valid link. Additionally, the "Recipient" is validated to ensure a real name has been entered.

