# calendar.api
## Synopsis
This document provides guidelines and examples for using calendar API.
## Content
The REST API Plugin provides the ability to mange the events from calendars by sending an REST/HTTP request to the server.
## Feature list
- get events from user calendar chronolologically or not
- CRUD operations on an event
- get all events of an user
- authenticate user
- create user
## Authentication
The api implements authentication based on token.To access the endpoints is required to send a secret key in the header request.
## User related REST Endpoints

1. Get calendar events

GET /calendar/events/<calendar_id>

Authentication: required
(1) Payload: none
   Return value: Events
(2) Payload: [sort=true]
   Return value: Events sorted  

------------------------------------
2. Get event

GET /event/<id>

Authentication: required
Payload: none
   Return value: Event

-------------------------------------

3. Create event

POST /event/create

Authentication: required
Payload: Event data 
Return value: Event

-------------------------------------

4. Update event

PUT /event/<id>

Authentication: required
Payload: Event data
Return value: Event

-------------------------------------

4. DELETE event

DELETE /event/<id>

Authentication: required
Payload: Event data
Return value: None

-------------------------------------

5. Get user events

GET /events

Authentication: required
Payload: None
Return value: Events

-------------------------------------

6. User authentication

GET /user/login

Authentication: required
Payload: {'username': username, 'password': password}
Return value: {user, token}

-------------------------------------

7. User create

GET /user/register

Authentication: required
Payload: User data
Return value: User

-------------------------------------










