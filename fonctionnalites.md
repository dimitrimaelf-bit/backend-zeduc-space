// PUBLIC
POST /api/register -> AuthController@register
POST /api/login -> AuthController@login
GET  /api/menu -> ProductController@index

// CLIENT connecté
POST /api/orders -> OrderController@store
GET  /api/my-orders -> OrderController@myOrders
GET  /api/my-points -> LoyaltyController@balance
POST /api/referral/generate -> ReferralController@generate  // génère ABC123
POST /api/referral/use -> ReferralController@use

// EMPLOYE + GERANT + ADMIN
GET  /api/employee/orders -> EmployeeOrderController@index
PATCH /api/employee/orders/{id}/validate -> EmployeeOrderController@validate

// GERANT + ADMIN
POST /api/employees -> EmployeeController@store

// ADMIN seulement
CRUD /api/admin/products -> AdminProductController
CRUD /api/admin/promotions -> PromotionController