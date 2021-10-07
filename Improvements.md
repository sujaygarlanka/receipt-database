## Receipt Database Fixes
- remove order confirmations
- total for receipts like monster where the dollar sign does not show after the word total or space after $
- gift cards excluded
- sync all emails with one refresh
- Base64 decoding sometimes doesn’t work in script (using try and catch as temp solution)
- Script is run for every 10 receipts because digital ocean shows internal server error for syncing more receipts (works on localhost and php settings were changed)

## Features
- Number of receipts left to be synced
- Budgeting tool that can be used to track expenses for college students
- Include attachments in receipt database
- search contents of receipts
- advanced search
- pie charts on categorization of spending
- budget and notifications
- show total of searched items in tables tab
- if total not there, than give a notification in the software with the option for the user to check the receipt and add the proper amount

## Decoding techniques
- Look for last total, then find dollar sign after it for plain body
- Look for last total, then find dollar sign after it for html body
- Not very reliable:
- Look for last bold, then find dollar sign

http://stackoverflow.com/questions/26613809/get-pdf-attachments-from-gmail-as-text/26623198#26623198
