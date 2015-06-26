Backend test
==============
You should create simple shopping basket backend API plus user authorization.
You **MUST** use Doctrine ORM for database structure and for working with database.
If you don't know Doctrine ORM which is actually not good for our project then you are allowed to work with database according to your knowledge.
It would be really good if you use TYPO3 Flow framework for this task. http://flow.typo3.org/download. If not - no problem, you can use what you are aware of :).
ONLY BACKEND PART. You don't need to create html, css, js and etc.

Task description
------------------
- You don't need to create admin part and methods for adding data into database. We think that all data is already in database. For testing
purposes you, of course, should add data into database but you are allowed to do this according to your concern.
- We have users with: login (max 20 symbols, required) and password (min 5 symbols, max 30 symbols, required).
- We have products with: title (max 256 symbols, required), description (max 1000 symbols), quantity (required, meaning: how many products we have),
price (required, example: 0.56, 10, 123.04), currency (default 'UAH', examples: UAH, USD, EUR, ...).
- Each product can have from 0 to 5 images. Images have: image_url (max 256 symbols, required) and title (max 100 symbols).
- We have categories with: name (max 100 symbols).
- Only one category could be assigned to product.
- Also we have exchange rates for currencies. Main currency is UAH.
- If you need additional fields you are welcome to add them.
- All requests are AJAX. Our responses are JSON.
- User can add products into basket. Only one product (with its quantity) per request.
- User can make an order.
- User can remove products from basket.
- Our frontend part can also provide extra requests:
- - get all images for a specific product.
- - get N products from specified category, starting from M position, ordered by title | price | quantity.
- - get products in basket for a current user
- - login
- - logout

Requirements
-----------------
- All sources should be committed to public source repository, such as: http://github.com, http://bitbucket.org, etc
- Please, provide us with a database dump with data to let us test you project.