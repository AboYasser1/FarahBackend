# Farah API Project

åĞÇ Çáãáİ íÔÑÍ ÇáãÔÑæÚ ÎØæÉ ÈÎØæÉ ÈÍíË íßæä ãÑÌÚÇ ááİåã æÇáÊØæíÑ.

## 1) ãŞÏãÉ ÇáãÔÑæÚ

åĞÇ ÇáãÔÑæÚ ãÈäí Úáì Laravel æíåÏİ Åáì ÈäÇÁ API ãäÇÓÈ áæÇÌåÉ Flutter. Êã ÊÕãíãå ÍÓÈ İßÑÉ ÇáÊØÈíŞ ÇáÊí ÊÙåÑ İí ÇáÕæÑ ÎÇÕÉ İí ÇáÃŞÓÇã ÇáÊÇáíÉ:
- ÇáÕİÍÉ ÇáÑÆíÓíÉ
- ÇáÊÕäíİÇÊ
- ÇáÎÏãÇÊ æÇáãäÊÌÇÊ
- ÊŞííãÇÊ ÇáÎÏãÇÊ
- ÇáãİÖáÉ
- ÇáÍÌæÒÇÊ
- ÅÚÏÇÏÇÊ ÇáÅÔÚÇÑÇÊ

---

## 2) åíßá ÇáãÔÑæÚ

### Core folders
- `app/Models` ? ÇáÌÏÇæá æModel classes
- `app/Http/Controllers/Api` ? Controllers ÇáÎÇÕÉ ÈÜ API
- `database/migrations` ? ÇáÌÏÇæá (Schema)
- `database/factories` ? Factory áÅäÔÇÁ ÈíÇäÇÊ ÊÌÑíÈíÉ
- `database/seeders` ? Seeder áãáÁ ÇáÈíÇäÇÊ
- `routes/api.php` ? ÌãíÚ endpoints ÇáÎÇÕÉ ÈÜ API

---

## 3) ÇáÌÏÇæá ÇáÃÓÇÓíÉ

### 3.1 users
ÇáÌÏæá ÇáÃÓÇÓí ááãÓÊÎÏãíä.

#### ÇáÃÚãÏÉ ÇáÃÓÇÓíÉ:
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

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃäå íÍÊæí Úáì ÈíÇäÇÊ ÇáãÓÊÎÏã ÇáÃÓÇÓíÉ ãËá ÇáÇÓã ÇáÅíãíá ßáãÉ ÇáãÑæÑ ÇáÍÇáÉ ÇáãÏíäÉ æÇáÕæÑÉ ÇáÔÎÕíÉ.

#### Model:
- `app/Models/User.php`

---

### 3.2 cities
ÇáÌÏæá ÇáĞí íÍİÙ ÇáãÏä.

#### ÇáÃÚãÏÉ:
- id
- name
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä ÇáÊØÈíŞ íÚÊãÏ Úáì ÇÎÊíÇÑ ÇáãÏíäÉ İí Çáãáİ ÇáÔÎÕí æÇáÎÏãÇÊ.

#### Model:
- `app/Models/City.php`

---

### 3.3 locations
åĞÇ ÇáÌÏæá íÍİÙ ÃãÇßä ÇáãÓÊÎÏã Ãæ ãÒæÏ ÇáÎÏãÉ.

#### ÇáÃÚãÏÉ:
- id
- user_id
- label
- address
- city_id
- latitude
- longitude
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃäå ãåã ÅĞÇ ßÇä ÇáÊØÈíŞ íÍÊÇÌ Åáì ÍİÙ ÃßËÑ ãä ÚäæÇä Ãæ ãæŞÚ ááãÓÊÎÏã.

#### Model:
- `app/Models/Location.php`

---

### 3.4 provider_profiles
åĞÇ ÇáÌÏæá íãËá ÈÑæİÇíá ÇáãÒæÏ.

#### ÇáÃÚãÏÉ:
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
- latitude
- longitude
- status
- is_featured
- rating
- working_hours
- timestamps

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä ÇáÊØÈíŞ íÍÊæí Úáì ãÒæÏíä ÎÏãÇÊ æßá ãÒæÏ íÍÊÇÌ ÇÓã Úãá ÍÇáÉ ÊŞííã ÕæÑÉ ÛáÇİ æãÚáæãÇÊ ÃÓÇÓíÉ.

#### Model:
- `app/Models/ProviderProfile.php`

---

### 3.5 categories
åĞÇ ÇáÌÏæá íÍÊæí ÇáÊÕäíİÇÊ Ãæ ÇáİÆÇÊ ÇáÑÆíÓíÉ ááÊØÈíŞ.

#### ÇáÃÚãÏÉ:
- id
- name
- slug
- image
- parent_id
- status
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä ÇáÔÇÔÉ ÇáÑÆíÓíÉ ÊÍÊæí Úáì ÊÕäíİÇÊ.

#### Model:
- `app/Models/Category.php`

---

### 3.6 services
åĞÇ åæ Ãåã ÌÏæá İí ÇáÊØÈíŞ ÈÚÏ users.

#### ÇáÃÚãÏÉ:
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

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃäå íãËá ÇáÎÏãÉ Ãæ ÇáãäÊÌ ÇáĞí íÚÑÖ İí ÇáÕİÍÉ ÇáÑÆíÓíÉ.

#### Model:
- `app/Models/Service.php`

---

### 3.7 service_images
åĞÇ ÇáÌÏæá íÍİÙ ÇáÕæÑ ÇáãÊÚÏÏÉ áßá ÎÏãÉ.

#### ÇáÃÚãÏÉ:
- id
- service_id
- image_path
- is_cover
- sort_order
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä ßá ÎÏãÉ ŞÏ íßæä áåÇ ÃßËÑ ãä ÕæÑÉ.

#### Model:
- `app/Models/ServiceImage.php`

---

### 3.8 reviews
åĞÇ ÇáÌÏæá íÍİÙ ÊŞííãÇÊ ÇáãÓÊÎÏãíä Úáì ÇáÎÏãÇÊ.

#### ÇáÃÚãÏÉ:
- id
- user_id
- service_id
- rating
- comment
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä ÇáÊØÈíŞ íÍÊæí Úáì äÌæã æÊŞííãÇÊ İí ßá ÎÏãÉ.

#### Model:
- `app/Models/Review.php`

---

### 3.9 favorites
åĞÇ ÇáÌÏæá íÍİÙ ÇáÎÏãÇÊ ÇáãİÖáÉ ÚäÏ ÇáãÓÊÎÏã.

#### ÇáÃÚãÏÉ:
- id
- user_id
- service_id
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä İí ÇáÔÇÔÉ íæÌÏ ÒÑ ŞáÈ Ãæ ãİÖáÉ.

#### Model:
- `app/Models/Favorite.php`

---

### 3.10 bookings
åĞÇ ÇáÌÏæá íÍİÙ ÇáÍÌæÒÇÊ.

#### ÇáÃÚãÏÉ:
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

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃäå ãäØŞ ÇáÊØÈíŞ íÊÖãä ÍÌÒ ÎÏãÇÊ Ãæ ÊÌåíÒÇÊ.

#### Model:
- `app/Models/Booking.php`

---

### 3.11 notification_settings
åĞÇ ÇáÌÏæá íÍİÙ ÅÚÏÇÏÇÊ ÇáÅÔÚÇÑÇÊ ááãÓÊÎÏã.

#### ÇáÃÚãÏÉ:
- id
- user_id
- new_orders
- offers
- promotions
- reminders
- created_at
- updated_at

#### áãÇĞÇ Êã ÅäÔÇÄå
áÃä İí ÇáÊÕãíã íæÌÏ ŞÓã ÇáÅÔÚÇÑÇÊ æãæÇÑÏ ãä äæÚ Toggle.

#### Model:
- `app/Models/NotificationSetting.php`

---

## 4) Models æãÇĞÇ íİÚá ßá æÇÍÏ

### `User`
íãËá ÇáãÓÊÎÏã ÇáÃÓÇÓí.

### `ProviderProfile`
íãËá ãÒæÏ ÇáÎÏãÉ.

### `Category`
íãËá ÇáİÆÉ Ãæ ÇáÊÕäíİ.

### `Service`
íãËá ÇáÎÏãÉ ÇáÍÇáíÉ ÇáãÚÑæÖÉ.

### `ServiceImage`
íãËá ÕæÑ ÇáÎÏãÉ.

### `Review`
íãËá ÊŞííã ÇáãÓÊÎÏã ááÎÏãÉ.

### `Favorite`
íãËá ÇáÎÏãÉ ÇáãİÖáÉ.

### `Booking`
íãËá ÇáÍÌÒ ÇáĞí Êã Úãáå.

### `NotificationSetting`
íãËá ÊİÖíáÇÊ ÇáÅÔÚÇÑÇÊ ááÜ user.

---

## 5) ãÇ åí ÇáÜ Controllers ÇáÊí Êã ÅäÔÇÄåÇ

### `HomeController`
æÙíİÊå:
- ÊÌåíÒ ÇáÕİÍÉ ÇáÑÆíÓíÉ
- ÌãÚ ÃÈÑÒ ÇáãÒæÏíä
- ÌãÚ ÇáÊÕäíİÇÊ
- ÌãÚ ÇáÎÏãÇÊ ÇáããíÒÉ

#### ÇáãÓÇÑ:
- `GET /api/home`

---

### `CategoryController`
æÙíİÊå:
- ÌáÈ ßá ÇáÊÕäíİÇÊ
- ÊäÙíãåÇ ÏÇÎá JSON

#### ÇáãÓÇÑ:
- `GET /api/categories`

---

### `ServiceController`
æÙíİÊå:
- ÌáÈ ßá ÇáÎÏãÇÊ
- ÇáÈÍË
- İáÊÑÉ ÍÓÈ category_id
- ÚÑÖ ÊİÇÕíá ÎÏãÉ ãÚíäÉ

#### ÇáãÓÇÑÇÊ:
- `GET /api/services`
- `GET /api/services/{id}`

---

## 6) áãÇĞÇ Êã ÅäÔÇÁ Controllers

áÃä Laravel íÍÊÇÌ ØÈŞÉ æÓíØÉ Èíä:
- ÇáÜ Route
- ÇáÜ Model
- æÇáÜ JSON Response

Ãí Ãä ÇáÜ Controller íÌíÈ Úáì ÇáØáÈ æíŞÑÃ ãä ŞÇÚÏÉ ÇáÈíÇäÇÊ Ëã íÚíÏ ÇáäÊíÌÉ Åáì Flutter.

---

## 7) ãÇ åí Factory æ Seeder

### Factory
åæ ãáİ íÓÇÚÏ Úáì ÅäÔÇÁ ÈíÇäÇÊ ÊÌÑíÈíÉ ÈÔßá ÓÑíÚ.

ÃãËáÉ:
- `CategoryFactory`
- `ServiceFactory`
- `ReviewFactory`
- `BookingFactory`
- `NotificationSettingFactory`

### Seeder
åæ ãáİ íŞæã ÈãáÁ ÌÏæá ãÚíä ÈÈíÇäÇÊ ÊÌÑíÈíÉ Ãæ ÃÓÇÓíÉ.

ÃãËáÉ:
- `CategorySeeder`
- `ServiceSeeder`
- `ReviewSeeder`
- `FavoriteSeeder`
- `BookingSeeder`
- `NotificationSettingSeeder`

### DatabaseSeeder
åæ Çáãáİ ÇáÑÆíÓí ÇáĞí íÑÈØ ßá ÇáÜ Seeders ãÚÇ.

---

## 8) áãÇĞÇ Factory æ Seeder ãåãÇä

áÃääÇ ÈÍÇÌÉ Åáì:
- ÇÎÊÈÇÑ ÇáÊØÈíŞ
- ãáÁ ŞÇÚÏÉ ÇáÈíÇäÇÊ ááÚÑÖ İí ÇáÊØÈíŞ
- ÇÎÊÈÇÑ ÇáÜ API ÈÔßá ÓÑíÚ
- ÅÚÏÇÏ ÈíÆÉ ÊØæíÑ ßÇãáÉ

---

## 9) Routes ÇáÃÓÇÓíÉ İí ÇáãÔÑæÚ

ÊãÊ ÅÖÇİÉ åĞå ÇáÑæÇÈØ İí `routes/api.php`:

- `GET /api/home`
- `GET /api/categories`
- `GET /api/services`
- `GET /api/services/{id}`

åĞÇ íÌÚá Flutter ŞÇÏÑÇ Úáì ÌáÈ ÇáÈíÇäÇÊ ãä Laravel ÈÔßá ãäÙã.

---

## 10) ÇáÎáÇÕÉ

åĞÇ ÇáãÔÑæÚ ÇáÂä íÊßæä ãä:
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

æåæ ÌÇåÒ ßÜ foundation ßÈíÑ ÌÏÇ áÈäÇÁ ÇáÊØÈíŞ ÈÇáßÇãá.

---

## 11) ÇáÎØæÉ ÇáÊÇáíÉ
íãßä ÇáÂä ãÊÇÈÚÉ ÈäÇÁ API ááÜ:
- Favorites
- Bookings
- Notification settings
- Reviews
- Auth/Profile

æåĞÇ ÓíßÊãá ÇáÊØÈíŞ ÈÔßá ÇÍÊÑÇİí æãäÇÓÈ ÌÏÇ áÜ Flutter.
