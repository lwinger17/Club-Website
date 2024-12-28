For this code to work please make sure the following info is in your mySQL database. 
(This info allows the admin to make changes on the site)

Call the db " ClubDatabase"

1.  "memberOrder"
![4dfef666-04e4-4350-9e58-1d477441882b](https://github.com/user-attachments/assets/df3d6bf2-538e-425e-ad28-1a1218a39b8a)
- this is used to allow the admin to change how the members appear on the members page

2. "members"
![5dd4c4d5-4a4a-4201-966c-5b43823ac52b](https://github.com/user-attachments/assets/e941d8b8-0615-43ca-bf05-91a661e2b41d)
- this is used to create / store / delete data about the members

3. "events"
![13172df7-fc17-4111-beb8-7599f557609f](https://github.com/user-attachments/assets/f6c7acef-caba-4f19-8345-813926e594dd)
- this is used to create / store / delete data about the events

4. "login"
![Screenshot_16-12-2024_94846_localhost](https://github.com/user-attachments/assets/e54214bc-c267-4596-9cd3-4b0c150d65b7)
- this is used to create / store data about the login
- Note that you can only login if the password is hashed.
- Requires a pre hashed username and password in the db before you can add new admin members
