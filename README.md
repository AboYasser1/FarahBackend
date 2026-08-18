# Farah API Project

هذا الملف يشرح المشروع خطوة بخطوة بالكامل ويستخدم كمرجع لفهم بنية المشروع والخدمات التي يقدمها للواجهة Flutter.

## 1) مقدمة المشروع

هذا المشروع مبني على Laravel ويهدف إلى بناء API مناسب لتطبيق Flutter. تم تصميمه بناء على فكرة التطبيق التي ظهرت في التصميم وبخاصة في الأقسام التالية:
- الصفحة الرئيسية
- التصنيفات
- الخدمات والمنتجات
- تقييمات الخدمات
- المفضلة
- الحجوزات
- إعدادات الإشعارات

---

## 2) هيكل المشروع

### مجلدات المشروع الأساسية
- app/Models → تمثل الجداول والـ Models
- app/Http/Controllers/Api → Controllers الخاصة بـ API
- database/migrations → ملفات إنشاء الجداول
- database/factories → إنشاء بيانات تجريبية
- database/seeders → إدخال بيانات أولية وتجريبية
- routes/api.php → كل مسارات الـ API

---

## 3) الجداول الأساسية في المشروع

### 3.1 users
هو الجدول الأساسي للمستخدمين.

#### الأعمدة الأساسية
- id
- name
- email
- password
- phone
- user_type
- status
- avatar
- bio
- cover_image
- last_login_at
- is_online
- city_id
- email_verified_at
- remember_token
- created_at
- updated_at
- deleted_at

#### لماذا تم إنشاؤه
لأنه يحتوي على البيانات الأساسية للمستخدم مثل الاسم الإيميل كلمة المرور المدينة الصورة الشخصية وحالة المستخدم.

#### Model المرتبط
- app/Models/User.php

---

### 3.2 cities
هو الجدول الذي يحتفظ بالمدن.

#### الأعمدة
- id
- name
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن التطبيق يعتمد على المدينة في الملف الشخصي والخدمات.

#### Model المرتبط
- app/Models/City.php

---

### 3.3 locations
هذا الجدول يحفظ مواقع المستخدم أو مزود الخدمة.

#### الأعمدة
- id
- user_id
- label
- address
- city_id
- latitude
- longitude
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأنه مفيد إذا كان التطبيق يحتاج إلى أكثر من عنوان أو موقع مرتبط بالمستخدم.

#### Model المرتبط
- app/Models/Location.php

---

### 3.4 provider_profiles
هذا الجدول يمثل ملف مزود الخدمة.

#### الأعمدة
- id
- user_id
- city_id
- business_name
- category_id
- phone
- bio
- description
- cover_image
- address
- status
- is_featured
- rating
- working_hours
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن التطبيق يحتوي على مزودين خدمات وكل مزود يحتاج إلى اسم عمل فئة تقييم صورة غلاف وحالة اعتماد.

#### Model المرتبط
- app/Models/ProviderProfile.php

---

### 3.5 categories
هذا الجدول يحتوي على التصنيفات الرئيسية للتطبيق.

#### الأعمدة
- id
- name
- slug
- image
- status
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن الصفحة الرئيسية والتصفح تحتاج إلى تصنيفات جاهزة لتقسيم الخدمات.

#### Model المرتبط
- app/Models/Category.php

---

### 3.6 services
هذا هو أهم جدول في التطبيق بعد جدول users.

#### الأعمدة
- id
- provider_id
- category_id
- city_id
- title
- description
- price
- currency
- image
- rating_avg
- reviews_count
- is_featured
- is_available
- status
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأنه يمثل الخدمة أو المنتج الذي يتم عرضه في التطبيق.

#### Model المرتبط
- app/Models/Service.php

---

### 3.7 service_images
هذا الجدول يخزن صور الخدمة المتعددة.

#### الأعمدة
- id
- service_id
- image_path
- sort_order
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن الخدمة قد تحتوي على أكثر من صورة وليس صورة واحدة فقط.

#### Model المرتبط
- app/Models/ServiceImage.php

---

### 3.8 reviews
هذا الجدول يخزن تقييمات المستخدمين للخدمات.

#### الأعمدة
- id
- user_id
- service_id
- rating
- comment
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن التطبيق يحتاج إلى تقييمات ونقاط للنجوم في كل خدمة.

#### Model المرتبط
- app/Models/Review.php

---

### 3.9 favorites
هذا الجدول يحفظ الخدمات المفضلة عند المستخدم.

#### الأعمدة
- id
- user_id
- service_id
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأنه مهم في شاشة المفضلة والعمليات التي تسمح للمستخدم بحفظ الخدمات.

#### Model المرتبط
- app/Models/Favorite.php

---

### 3.10 bookings
هذا الجدول يحفظ الحجوزات أو الطلبات المخصصة للخدمة.

#### الأعمدة
- id
- user_id
- service_id
- provider_id
- booking_date
- booking_time
- total_price
- status
- notes
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن التطبيق يحتوي على مفهوم حجز الخدمة من قبل المستخدم.

#### Model المرتبط
- app/Models/Booking.php

---

### 3.11 notification_settings
هذا الجدول يحفظ إعدادات إشعارات المستخدم.

#### الأعمدة
- id
- user_id
- new_orders
- offers
- promotions
- reminders
- created_at
- updated_at

#### لماذا تم إنشاؤه
لأن التطبيق يحتوي على شاشة إعدادات الإشعارات toggles وهذا يحتاج إلى جدول مستقل.

#### Model المرتبط
- app/Models/NotificationSetting.php

---

## 4) شرح Models الأساسية

### User
يمثل المستخدم الرئيسي في التطبيق.

### ProviderProfile
يمثل ملف مزود الخدمة.

### Category
يمثل التصنيف أو الفئة.

### Service
يمثل الخدمة نفسها.

### ServiceImage
يمثل صور الخدمة.

### Review
يمثل تقييم الخدمة من العميل.

### Favorite
يمثل خدمة مفضلة للمستخدم.

### Booking
يمثل حجز أو طلب الخدمة.

### NotificationSetting
يمثل إعدادات الإشعارات الخاصة بالمستخدم.

---

## 5) ما هي Controllers التي تم إنشاؤها

### HomeController
وظيفته:
- تجهيز بيانات الصفحة الرئيسية
- جمع أبرز المزودين
- جمع التصنيفات
- جمع الخدمات المميزة

#### المسار
- GET /api/home

---

### CategoryController
وظيفته:
- جلب جميع التصنيفات
- تحويلها إلى JSON للواجهة

#### المسار
- GET /api/categories

---

### ServiceController
وظيفته:
- جلب الخدمات
- البحث حسب الاسم
- فلترة حسب category_id
- عرض تفاصيل الخدمة حسب ID

#### المسارات
- GET /api/services
- GET /api/services/{id}

---

## 6) لماذا نحتاج الـ Controller

لأن Laravel يحتاج طبقة وسيطة بين:
- Route
- Model
- JSON Response

أي أن الـ Controller يستقبل الطلب يطلب البيانات من قاعدة البيانات ثم يرجع النتيجة إلى Flutter بصيغة JSON.

---

## 7) ما هي Factory و Seeder

### Factory
هو ملف يساعد في إنشاء بيانات تجريبية بسرعة.

أمثلة:
- CategoryFactory
- ServiceFactory
- ReviewFactory
- BookingFactory
- NotificationSettingFactory

### Seeder
هو ملف يقوم بملء جدول معين ببيانات أولية أو تجريبية.

أمثلة:
- CategorySeeder
- ServiceSeeder
- ReviewSeeder
- FavoriteSeeder
- BookingSeeder
- NotificationSettingSeeder

### DatabaseSeeder
هو الملف الرئيسي الذي يربط جميع الـ Seeders معا.

---

## 8) لماذا Factory و Seeder مهمان

لأننا نحتاج إلى:
- اختبار التطبيق
- تعبئة قاعدة البيانات للعرض
- اختبار الـ API بسرعة
- إعداد بيئة تطوير كاملة

---

## 9) الـ Routes الأساسية في المشروع

هذه هي المسارات الأساسية المضافة في routes/api.php:

- GET /api/home
- GET /api/categories
- GET /api/services
- GET /api/services/{id}

هذه المسارات جاهزة بحيث يمكن لواجهة Flutter استدعاؤها مباشرة.

---

## 10) الخلاصة

المشروع الآن يتكون من:
- Users + Auth
- Cities
- Locations
- Provider Profiles
- Categories
- Services
- Reviews
- Favorites
- Bookings
- Notification Settings
- API controllers
- Factory + Seeder

وهذا يشكل أساسا قويا جدا لبناء التطبيق بالكامل بشكل احترافي.

---

## 11) الخطوة التالية
يمكن الآن متابعة تطوير الـ API للجزء التالي:
- Favorites
- Bookings
- Notification settings
- Reviews
- Auth/Profile

وهذا سيكمل التطبيق بشكل أكثر احترافية ومناسب جدا لواجهة Flutter.
