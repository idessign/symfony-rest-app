<h2>Test symfony-rest-app</h2>

<strong>Create "countStudents" cache</strong><br>
/school?cache=create<br>
/school/{id}?cache=create<br>

<strong>Routes and Methods</strong><br>
GET /school - school_list - with school attributes + studentCount (need to read cache if exists, if not need to create cache)<br>
GET /school/{id} - school_show - with school attributes + studentCount (need to read cache if exists, if not need to create cache)<br>
POST /school - school_new<br>
PUT /school/{id} - school_update<br>
DELETE /school/{id} - school_delete<br>

GET /student - student_list<br>
GET /student/{id} - student_show<br>
POST /student - student_new<br>
PUT /student/{id} - student_update<br>
DELETE /student/{id} - student_delete<br>
