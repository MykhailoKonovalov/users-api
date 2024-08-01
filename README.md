### Setting up for local environment

- Install **docker** and **docker-compose**
- Run `make start`
- That's it!

The database dump file `users_db_dump.sql` is located in the root folder of the project. 
However, the `make start` command would be enough to create the database and execute migrations and fixtures.

#### Some notes on the task:

- I didn't hash the password, because based on the task, it's not needed.
- It seems to me that in the table “Required attributes for requests” the attributes for POST and PUT should be swapped,
because it will be more correct.
- As I understood from the task, it's not necessary to create a real login, so one of the "hard-coded" users is 
selected during authorization via token.
- Since the POST request is used to create a new user, I decided not to restrict access to POST for users with 
the "testUser" token.