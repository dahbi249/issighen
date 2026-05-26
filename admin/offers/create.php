<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$editId = (int)($_GET['id'] ?? 0);
$isEdit = $editId > 0;
$pageTitle = $isEdit ? ($lang === 'ar' ? 'تعديل العرض' : ($lang === 'fr' ? 'Modifier l\'offre' : 'Edit Offer'))
    : ($lang === 'ar' ? 'إضافة عرض جديد' : ($lang === 'fr' ? 'Ajouter une offre' : 'Add New Offer'));
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>
    <div class="admin-layout">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;flex-shrink:0;">✈</div>
                <div class="sidebar-logo"><?= htmlspecialchars($t['site_name']) ?><br><span class="admin-role-badge"><?= $lang === 'ar' ? 'لوحة التحكم' : ($lang === 'fr' ? 'Panneau Admin' : 'Admin Panel') ?></span></div>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span><?= $lang === 'ar' ? 'لوحة التحكم' : ($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></a>
                <a href="../reservations/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= $lang === 'ar' ? 'الحجوزات' : ($lang === 'fr' ? 'Réservations' : 'Reservations') ?></a>
                <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= $lang === 'ar' ? 'العروض' : ($lang === 'fr' ? 'Offres' : 'Offers') ?></a>
                <a href="../users/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang === 'ar' ? 'المستخدمون' : ($lang === 'fr' ? 'Utilisateurs' : 'Users') ?></a>
                <a href="../contacts/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-envelope"></i></span><?= $lang === 'ar' ? 'الرسائل' : ($lang === 'fr' ? 'Messages' : 'Messages') ?></a>
            </nav>
            <div class="sidebar-footer">
                <a href="/issighen/public/auth/logout.php"><i class="fas fa-sign-out-alt"></i><?= htmlspecialchars($t['nav_logout']) ?></a>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:12px;">
                    <button id="sidebarToggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
                    <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= htmlspecialchars($pageTitle) ?></h1>
                </div>
                <a href="index.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-arrow-<?= $dir === 'rtl' ? 'right' : 'left' ?>"></i> <?= htmlspecialchars($t['btn_back'] ?? 'Back') ?>
                </a>
            </div>
            <?= display_flash_messages() ?>

            <form method="POST" action="process.php?lang=<?= $lang ?>" enctype="multipart/form-data" novalidate>
                <?php if (function_exists('generate_csrf_token')): ?>
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <?php endif; ?>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="offer_id" value="<?= $editId ?>">
                <?php endif; ?>

                <div class="admin-grid-2">
                    <!-- Left: Offer Content -->
                    <div>
                        <div class="card" style="margin-bottom:20px;">
                            <div class="card-header">
                                <h3><?= $lang === 'ar' ? 'محتوى العرض' : ($lang === 'fr' ? 'Contenu de l\'offre' : 'Offer Content') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العنوان (عربي)' : ($lang === 'fr' ? 'Titre (Arabe)' : 'Title (Arabic)') ?></label>
                                    <input type="text" name="title_ar" class="form-control" dir="rtl" placeholder="برنامج العمرة المميز">
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العنوان (فرنسي)' : ($lang === 'fr' ? 'Titre (Français)' : 'Title (French)') ?></label>
                                    <input type="text" name="title_fr" class="form-control" placeholder="Programme Omra Premium">
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العنوان (إنجليزي)' : ($lang === 'fr' ? 'Titre (Anglais)' : 'Title (English)') ?></label>
                                    <input type="text" name="title_en" class="form-control" placeholder="Premium Umrah Package">
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الوصف (عربي)' : ($lang === 'fr' ? 'Description (Arabe)' : 'Description (Arabic)') ?></label>
                                    <textarea name="desc_ar" class="form-control" rows="4" dir="rtl" placeholder="أدخل وصف العرض بالعربية..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الوصف (فرنسي)' : ($lang === 'fr' ? 'Description (Français)' : 'Description (French)') ?></label>
                                    <textarea name="desc_fr" class="form-control" rows="4" placeholder="Entrez la description en français..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الوصف (إنجليزي)' : ($lang === 'fr' ? 'Description (Anglais)' : 'Description (English)') ?></label>
                                    <textarea name="desc_en" class="form-control" rows="4" placeholder="Enter English description..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Includes list -->
                        <div class="card">
                            <div class="card-header">
                                <h3><?= $lang === 'ar' ? 'ما يشمله العرض' : ($lang === 'fr' ? 'Ce qui est inclus' : 'What\'s Included') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العناصر (عربي — سطر لكل عنصر)' : ($lang === 'fr' ? 'Éléments (Arabe — un par ligne)' : 'Items (Arabic — one per line)') ?></label>
                                    <textarea name="includes_ar" class="form-control" rows="5" dir="rtl" placeholder="إقامة فندقية 5 نجوم&#10;تذاكر طيران&#10;مرشد ديني"></textarea>
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العناصر (فرنسي)' : ($lang === 'fr' ? 'Éléments (Français)' : 'Items (French)') ?></label>
                                    <textarea name="includes_fr" class="form-control" rows="5" placeholder="Hébergement 5 étoiles&#10;Billets d'avion&#10;Guide religieux"></textarea>
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'العناصر (إنجليزي)' : ($lang === 'fr' ? 'Éléments (Anglais)' : 'Items (English)') ?></label>
                                    <textarea name="includes_en" class="form-control" rows="5" placeholder="5-star hotel&#10;Round-trip flights&#10;Religious guide"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Settings + Image -->
                    <div>
                        <div class="card" style="margin-bottom:20px;">
                            <div class="card-header">
                                <h3><?= $lang === 'ar' ? 'إعدادات العرض' : ($lang === 'fr' ? 'Paramètres' : 'Offer Settings') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الفئة' : ($lang === 'fr' ? 'Catégorie' : 'Category') ?></label>
                                    <select name="category" class="form-control">
                                        <option value="umrah"><?= $lang === 'ar' ? 'عمرة' : ($lang === 'fr' ? 'Omra' : 'Umrah') ?></option>
                                        <option value="hajj"><?= $lang === 'ar' ? 'حج' : 'Hajj' ?></option>
                                        <option value="tourism"><?= $lang === 'ar' ? 'سياحة' : ($lang === 'fr' ? 'Tourisme' : 'Tourism') ?></option>
                                        <option value="other"><?= $lang === 'ar' ? 'أخرى' : ($lang === 'fr' ? 'Autre' : 'Other') ?></option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><?= $lang === 'ar' ? 'السعر (DA)' : ($lang === 'fr' ? 'Prix (DA)' : 'Price (DA)') ?></label>
                                        <input type="number" name="price" class="form-control" min="0" placeholder="85000">
                                    </div>
                                    <div class="form-group">
                                        <label><?= $lang === 'ar' ? 'عدد الأيام' : ($lang === 'fr' ? 'Nombre de jours' : 'Number of Days') ?></label>
                                        <input type="number" name="days" class="form-control" min="1" placeholder="10">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الموقع' : ($lang === 'fr' ? 'Lieu' : 'Location') ?></label>
                                    <input type="text" name="location" class="form-control" placeholder="<?= $lang === 'ar' ? 'مكة المكرمة' : ($lang === 'fr' ? 'La Mecque' : 'Makkah') ?>">
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الحالة' : ($lang === 'fr' ? 'Statut' : 'Status') ?></label>
                                    <select name="status" class="form-control">
                                        <option value="active"><?= $lang === 'ar' ? 'نشط' : ($lang === 'fr' ? 'Actif' : 'Active') ?></option>
                                        <option value="inactive"><?= $lang === 'ar' ? 'غير نشط' : ($lang === 'fr' ? 'Inactif' : 'Inactive') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                        <input type="checkbox" name="featured" value="1">
                                        <?= $lang === 'ar' ? 'عرض مميز' : ($lang === 'fr' ? 'Offre vedette' : 'Featured Offer') ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Image upload -->
                        <div class="card" style="margin-bottom:20px;">
                            <div class="card-header">
                                <h3><?= $lang === 'ar' ? 'صورة العرض' : ($lang === 'fr' ? 'Image de l\'offre' : 'Offer Image') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:var(--text-light);display:block;margin-bottom:10px;"></i>
                                    <p style="color:var(--text-light);font-size:.9rem;margin:0 0 8px;"><?= $lang === 'ar' ? 'اسحب الصورة هنا أو' : ($lang === 'fr' ? 'Glissez l\'image ici ou' : 'Drag image here or') ?></p>
                                    <label for="offerImage" class="btn btn-outline btn-sm" style="cursor:pointer;"><?= $lang === 'ar' ? 'اختر ملف' : ($lang === 'fr' ? 'Choisir fichier' : 'Choose File') ?></label>
                                    <input type="file" name="image" id="offerImage" accept="image/*" style="display:none;">
                                </div>
                                <div id="imagePreview" style="display:none;margin-top:12px;">
                                    <img id="previewImg" src="" alt="preview" style="width:100%;border-radius:var(--radius-sm);max-height:200px;object-fit:cover;">
                                    <button type="button" id="removeImage" class="btn btn-danger btn-sm" style="margin-top:8px;width:100%;">
                                        <i class="fas fa-trash"></i> <?= $lang === 'ar' ? 'إزالة الصورة' : ($lang === 'fr' ? 'Supprimer l\'image' : 'Remove Image') ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding:14px;font-size:1rem;">
                            <i class="fas fa-save"></i> <?= $isEdit ? ($lang === 'ar' ? 'حفظ التغييرات' : ($lang === 'fr' ? 'Enregistrer les modifications' : 'Save Changes')) : ($lang === 'ar' ? 'نشر العرض' : ($lang === 'fr' ? 'Publier l\'offre' : 'Publish Offer')) ?>
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../../assets/js/main.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.remove('open');
            this.classList.remove('active');
        });
        // Image preview
        document.getElementById('offerImage')?.addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadArea').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
        document.getElementById('removeImage')?.addEventListener('click', function() {
            document.getElementById('offerImage').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadArea').style.display = 'block';
        });
        // Drag and drop
        var uploadArea = document.getElementById('uploadArea');
        uploadArea?.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--gold)';
        });
        uploadArea?.addEventListener('dragleave', function() {
            this.style.borderColor = '';
        });
        uploadArea?.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '';
            var file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                var input = document.getElementById('offerImage');
                if (typeof DataTransfer === 'function') {
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                }
                var reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('previewImg').src = ev.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadArea').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>