# YourSurprise application assessment 
Database file can be found in this repository: https://github.com/YourSurpriseCom/database-test 

## Backend System (PHP REST API) 
Develop a PHP application using a framework of your choice that connects to the 
yoursurprise_test.db database and exposes a RESTful API, the API must allow for basic 
interaction with the product catalog: 
 
●  Catalog Browsing: Implement a mechanism to retrieve a paginated list of currently 
active products (active = 1). 
●  Product Detail: Implement a mechanism to retrieve the full details of any product, 
including a clear representation of its associated category name. 
●  Cart Retrieval: Provide an endpoint that, given a Cart ID, returns a detailed view of all 
items in that cart. The response should include the product name, quantity, and the final 
calculated price for each item (after applying its discount). 
 
## Frontend Visualization (JavaScript Application) 
Create a single-page frontend application using JavaScript (and a library/framework/toolchain of 
your choice) that consumes your custom REST API. 
Dynamic Cart Summary: 
●  Goal: Provide a live, detailed view of a specific shopping cart. 
●  Requirement: Allow a user to input a Cart ID and dynamically fetch and display the cart 
contents. 
## AI Integration (Bonus/Challenge) 
This assignment requires you to leverage the power of an external Large Language Model (e.g., 
OpenAI, Gemini) and combine it with your application. 
 
## Submission and Evaluation 
1.  Source Code: Provide a link to a GitHub repository with all source code. Include a 
clear README.md with instructions for: 
○  Installation and setup of the application. 
○  Database connection and initialization (using the provided .db file). 
○  How to run the frontend application. 
2.  Presentation (30 minutes): Make clear how you went about it and explain the different 
choices you made. Not necessarily a powerpoint presentation ;).. We would rather have 
you opening the code and walking us through.