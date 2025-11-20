<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - CRUD PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .badge-custom {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .tag-input {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            min-height: 38px;
        }
        .tag {
            background: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .tag-remove {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0"><i class="bi bi-box-seam"></i> Gestion des Produits</h1>
                <div>
                    <span class="me-3" id="user-info"></span>
                    <button class="btn btn-outline-danger btn-sm" onclick="logout()">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulaire d'ajout/modification -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="form-section">
                    <h3 id="form-title" class="mb-3">Ajouter un produit</h3>
                    
                    <!-- Onglets pour organiser le formulaire -->
                    <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                <i class="bi bi-info-circle"></i> Informations de base
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button">
                                <i class="bi bi-images"></i> Médias
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">
                                <i class="bi bi-list-check"></i> Détails
                            </button>
                        </li>
                    </ul>

                    <form id="product-form">
                        <input type="hidden" id="product-id" name="id">
                        
                        <div class="tab-content" id="productTabsContent">
                            <!-- Onglet Informations de base -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nom du produit *</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="categoryId" class="form-label">Nom de la catégorie *</label>
                                        <select class="form-select" id="categoryId" name="categoryId" required>
                                            <option value="">Sélectionner une catégorie</option>
                                        </select>
                                        <input type="hidden" id="category" name="category">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="brand" class="form-label">Marque *</label>
                                        <input type="text" class="form-control" id="brand" name="brand" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ageRange" class="form-label">Tranche d'âge *</label>
                                        <input type="text" class="form-control" id="ageRange" name="ageRange" placeholder="ex: 0-36 mois" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="shortDescription" class="form-label">Description courte</label>
                                    <textarea class="form-control" id="shortDescription" name="shortDescription" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description complète</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="price" class="form-label">Prix (€) *</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="originalPrice" class="form-label">Prix original (€)</label>
                                        <input type="number" step="0.01" class="form-control" id="originalPrice" name="originalPrice">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discountPercentage" class="form-label">Remise (%)</label>
                                        <input type="number" class="form-control" id="discountPercentage" name="discountPercentage">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="availability" class="form-label">Disponibilité *</label>
                                        <select class="form-select" id="availability" name="availability" required>
                                            <option value="in_stock">En stock</option>
                                            <option value="low_stock">Stock faible</option>
                                            <option value="out_of_stock">Rupture de stock</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="badge" class="form-label">Badge</label>
                                        <select class="form-select" id="badge" name="badge">
                                            <option value="">Aucun</option>
                                            <option value="new">Nouveau</option>
                                            <option value="sale">Promotion</option>
                                            <option value="trending">Tendance</option>
                                            <option value="bestseller">Best-seller</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="isNew" name="isNew">
                                            <label class="form-check-label" for="isNew">
                                                Produit nouveau
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rating" class="form-label">Note (0-5) *</label>
                                        <input type="number" step="0.1" min="0" max="5" class="form-control" id="rating" name="rating" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="reviewCount" class="form-label">Nombre d'avis *</label>
                                        <input type="number" class="form-control" id="reviewCount" name="reviewCount" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Médias -->
                            <div class="tab-pane fade" id="media" role="tabpanel">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image principale (URL)</label>
                                    <input type="url" class="form-control" id="image" name="image" placeholder="https://...">
                                </div>
                                <div class="mb-3">
                                    <label for="images" class="form-label">Images supplémentaires (URLs, une par ligne)</label>
                                    <textarea class="form-control" id="images" name="images" rows="4" placeholder="https://image1.com&#10;https://image2.com"></textarea>
                                    <small class="text-muted">Une URL par ligne</small>
                                </div>
                                <div class="mb-3">
                                    <label for="videos" class="form-label">Vidéos (URLs Cloudinary, une par ligne)</label>
                                    <textarea class="form-control" id="videos" name="videos" rows="3" placeholder="https://res.cloudinary.com/..."></textarea>
                                    <small class="text-muted">Une URL par ligne</small>
                                </div>
                            </div>

                            <!-- Onglet Détails -->
                            <div class="tab-pane fade" id="details" role="tabpanel">
                                <div class="mb-3">
                                    <label for="material" class="form-label">Matériau</label>
                                    <input type="text" class="form-control" id="material" name="material">
                                </div>
                                <div class="mb-3">
                                    <label for="sizes" class="form-label">Tailles (une par ligne)</label>
                                    <textarea class="form-control" id="sizes" name="sizes" rows="3" placeholder="0-3M&#10;3-6M&#10;6-9M"></textarea>
                                    <small class="text-muted">Une taille par ligne</small>
                                </div>
                                <div class="mb-3">
                                    <label for="colors" class="form-label">Couleurs (une par ligne)</label>
                                    <textarea class="form-control" id="colors" name="colors" rows="3" placeholder="Noir&#10;Marron&#10;Blanc"></textarea>
                                    <small class="text-muted">Une couleur par ligne</small>
                                </div>
                                <div class="mb-3">
                                    <label for="features" class="form-label">Caractéristiques (une par ligne)</label>
                                    <textarea class="form-control" id="features" name="features" rows="4" placeholder="4 saisons&#10;Tissu respirant&#10;Lavable en machine"></textarea>
                                    <small class="text-muted">Une caractéristique par ligne</small>
                                </div>
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                                    <input type="text" class="form-control" id="tags" name="tags" placeholder="premium, bébé, 4 saisons">
                                    <small class="text-muted">Séparez les tags par des virgules</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> <span id="submit-text">Ajouter</span>
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancel-btn" style="display: none;" onclick="resetForm()">
                                <i class="bi bi-x-circle"></i> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Liste des produits</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadProducts()">
                            <i class="bi bi-arrow-clockwise"></i> Actualiser
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Marque</th>
                                        <th>Prix</th>
                                        <th>Disponibilité</th>
                                        <th>Note</th>
                                        <th>Badge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table">
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Chargement...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de changement de mot de passe (première utilisation) -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Changement de mot de passe requis</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i> C'est votre première connexion. Veuillez changer le mot de passe par défaut pour sécuriser votre compte.
                    </div>
                    <form id="change-password-form">
                        <div class="mb-3">
                            <label for="current-password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current-password" value="admin123" readonly>
                            <small class="text-muted">Mot de passe par défaut</small>
                        </div>
                        <div class="mb-3">
                            <label for="new-password" class="form-label">Nouveau mot de passe *</label>
                            <input type="password" class="form-control" id="new-password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Confirmer le nouveau mot de passe *</label>
                            <input type="password" class="form-control" id="confirm-password" required minlength="6">
                        </div>
                        <div id="password-error" class="alert alert-danger" style="display: none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save-password-btn">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'api/products.php';
        const CATEGORIES_URL = 'api/categories.php';
        let deleteProductId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        let categories = [];

        // Charger les catégories au démarrage
        function handleCategoryChange() {
            const select = document.getElementById('categoryId');
            const hiddenCategory = document.getElementById('category');
            if (!select || !hiddenCategory) return;
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                hiddenCategory.value = selectedOption.textContent.trim();
            } else {
                hiddenCategory.value = '';
            }
        }

        function loadCategories() {
            fetch(CATEGORIES_URL, {
                headers: getAuthHeaders()
            })
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    categories = data;
                    const select = document.getElementById('categoryId');
                    select.innerHTML = '<option value="">Sélectionner une catégorie</option>';
                    data.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.name;
                        select.appendChild(option);
                    });
                    select.onchange = handleCategoryChange;
                    handleCategoryChange();
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des catégories:', error);
            });
        }

        // Vérifier l'authentification au chargement
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            checkFirstUse();
            loadCategories();
            loadProducts();
        });

        // Vérifier si c'est la première utilisation
        function checkFirstUse() {
            const user = JSON.parse(localStorage.getItem('user') || 'null');
            if (user && user.username === 'admin') {
                fetch('api/check-first-use.php', {
                    headers: getAuthHeaders()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.isFirstUse) {
                        const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                        changePasswordModal.show();
                    }
                })
                .catch(() => {});
            }
        }

        // Vérifier si l'utilisateur est authentifié
        function checkAuth() {
            const token = localStorage.getItem('jwt_token');
            const user = JSON.parse(localStorage.getItem('user') || 'null');
            
            if (!token) {
                window.location.href = 'login.php';
                return;
            }
            
            if (user) {
                document.getElementById('user-info').innerHTML = 
                    `<i class="bi bi-person-circle"></i> <strong>${user.username}</strong> (${user.role})`;
            }
        }

        // Obtenir le token pour les requêtes
        function getAuthHeaders() {
            const token = localStorage.getItem('jwt_token');
            return {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            };
        }

        // Déconnexion
        function logout() {
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            window.location.href = 'login.php';
        }

        // Gérer la soumission du formulaire
        document.getElementById('product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            
            // Traiter les champs normaux
            formData.forEach((value, key) => {
                if (value !== '') {
                    data[key] = value;
                }
            });
            
            // Traiter les tableaux (images, videos, sizes, colors, features, tags)
            if (data.images) {
                data.images = data.images.split('\n').filter(url => url.trim() !== '');
            } else {
                data.images = [];
            }
            
            if (data.videos) {
                data.videos = data.videos.split('\n').filter(url => url.trim() !== '');
            } else {
                data.videos = [];
            }
            
            if (data.sizes) {
                data.sizes = data.sizes.split('\n').filter(size => size.trim() !== '');
            } else {
                data.sizes = [];
            }
            
            if (data.colors) {
                data.colors = data.colors.split('\n').filter(color => color.trim() !== '');
            } else {
                data.colors = [];
            }
            
            if (data.features) {
                data.features = data.features.split('\n').filter(feature => feature.trim() !== '');
            } else {
                data.features = [];
            }
            
            if (data.tags) {
                data.tags = data.tags.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
            } else {
                data.tags = [];
            }
            
            // Convertir les types
            data.price = parseFloat(data.price) || 0;
            data.originalPrice = data.originalPrice ? parseFloat(data.originalPrice) : null;
            data.discountPercentage = data.discountPercentage ? parseInt(data.discountPercentage) : null;
            data.rating = parseFloat(data.rating) || 0;
            data.reviewCount = parseInt(data.reviewCount) || 0;
            data.isNew = document.getElementById('isNew').checked;
            
            // Calculer le discount si originalPrice et price sont fournis
            if (data.originalPrice && data.price && !data.discountPercentage) {
                data.discount = data.originalPrice - data.price;
                data.discountPercentage = Math.round((data.discount / data.originalPrice) * 100);
            }

            const productId = document.getElementById('product-id').value;
            const method = productId ? 'PUT' : 'POST';
            const url = productId ? `${API_URL}?id=${productId}` : API_URL;

            fetch(url, {
                method: method,
                headers: getAuthHeaders(),
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.status === 401) {
                    logout();
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                
                if (data.message || data.success) {
                    alert(data.message || (data.success ? 'Opération réussie' : 'Erreur'));
                    resetForm();
                    loadProducts();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur de connexion au serveur. Vérifiez votre connexion.');
            });
        });

        // Charger tous les produits
        function loadProducts() {
            fetch(API_URL, {
                headers: getAuthHeaders()
            })
            .then(response => {
                if (response.status === 401) {
                    logout();
                    return null;
                }
                if (!response.ok) {
                    throw new Error('Erreur HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(products => {
                if (!products) return;
                
                const tbody = document.getElementById('products-table');
                
                if (!Array.isArray(products)) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-center text-warning">Aucun produit dans la base de données</td></tr>';
                    return;
                }
                
                if (products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-center">Aucun produit trouvé</td></tr>';
                    return;
                }

                tbody.innerHTML = products.map(product => {
                    const availabilityBadge = {
                        'in_stock': 'bg-success',
                        'low_stock': 'bg-warning',
                        'out_of_stock': 'bg-danger'
                    }[product.availability] || 'bg-secondary';
                    
                    const availabilityText = {
                        'in_stock': 'En stock',
                        'low_stock': 'Stock faible',
                        'out_of_stock': 'Rupture'
                    }[product.availability] || product.availability;

                    return `
                        <tr>
                            <td>${product.id}</td>
                            <td>
                                ${product.image ? `<img src="${product.image}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : '<i class="bi bi-image"></i>'}
                            </td>
                            <td><strong>${product.name}</strong></td>
                            <td><span class="badge bg-info">${product.category}</span></td>
                            <td>${product.brand || '-'}</td>
                            <td>
                                ${product.originalPrice ? `<span class="text-decoration-line-through text-muted">${product.originalPrice}€</span> ` : ''}
                                <strong>${product.price}€</strong>
                                ${product.discountPercentage ? `<span class="badge bg-danger">-${product.discountPercentage}%</span>` : ''}
                            </td>
                            <td><span class="badge ${availabilityBadge}">${availabilityText}</span></td>
                            <td>
                                <i class="bi bi-star-fill text-warning"></i> ${product.rating}
                                <small class="text-muted">(${product.reviewCount || 0})</small>
                            </td>
                            <td>
                                ${product.badge ? `<span class="badge bg-primary badge-custom">${product.badge}</span>` : '-'}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editProduct('${product.id}')" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('${product.id}')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            })
            .catch(error => {
                console.error('Erreur:', error);
                const tbody = document.getElementById('products-table');
                tbody.innerHTML = 
                    '<tr><td colspan="10" class="text-center text-danger">' +
                    'Erreur lors du chargement des produits. ' +
                    'Vérifiez que la table "products" existe dans la base de données.' +
                    '</td></tr>';
            });
        }

        // Modifier un produit
        function editProduct(id) {
            fetch(`${API_URL}?id=${id}`, {
                headers: getAuthHeaders()
            })
            .then(response => response.json())
            .then(product => {
                if (!product) {
                    alert('Produit non trouvé');
                    return;
                }
                
                document.getElementById('product-id').value = product.id;
                document.getElementById('name').value = product.name || '';
                const categorySelect = document.getElementById('categoryId');
                const categoryValue = product.categoryId || '';
                if (categoryValue && ![...categorySelect.options].some(opt => opt.value === categoryValue)) {
                    const newOption = document.createElement('option');
                    newOption.value = categoryValue;
                    newOption.textContent = product.category || categoryValue;
                    categorySelect.appendChild(newOption);
                }
                categorySelect.value = categoryValue;
                document.getElementById('category').value = product.category || '';
                handleCategoryChange();
                document.getElementById('brand').value = product.brand || '';
                document.getElementById('ageRange').value = product.ageRange || '';
                document.getElementById('shortDescription').value = product.shortDescription || '';
                document.getElementById('description').value = product.description || '';
                document.getElementById('price').value = product.price || '';
                document.getElementById('originalPrice').value = product.originalPrice || '';
                document.getElementById('discountPercentage').value = product.discountPercentage || '';
                document.getElementById('availability').value = product.availability || 'in_stock';
                document.getElementById('badge').value = product.badge || '';
                document.getElementById('isNew').checked = product.isNew || false;
                document.getElementById('rating').value = product.rating || '';
                document.getElementById('reviewCount').value = product.reviewCount || 0;
                document.getElementById('image').value = product.image || '';
                document.getElementById('images').value = Array.isArray(product.images) ? product.images.join('\n') : '';
                document.getElementById('videos').value = Array.isArray(product.videos) ? product.videos.join('\n') : '';
                document.getElementById('material').value = product.material || '';
                document.getElementById('sizes').value = Array.isArray(product.sizes) ? product.sizes.join('\n') : '';
                document.getElementById('colors').value = Array.isArray(product.colors) ? product.colors.join('\n') : '';
                document.getElementById('features').value = Array.isArray(product.features) ? product.features.join('\n') : '';
                document.getElementById('tags').value = Array.isArray(product.tags) ? product.tags.join(', ') : '';

                document.getElementById('form-title').textContent = 'Modifier le produit';
                document.getElementById('submit-text').textContent = 'Modifier';
                document.getElementById('cancel-btn').style.display = 'inline-block';

                // Activer l'onglet de base
                const basicTab = new bootstrap.Tab(document.getElementById('basic-tab'));
                basicTab.show();

                // Scroll vers le formulaire
                document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement du produit');
            });
        }

        // Confirmer la suppression
        function confirmDelete(id) {
            deleteProductId = id;
            deleteModal.show();
        }

        // Supprimer un produit
        document.getElementById('confirm-delete').addEventListener('click', function() {
            if (deleteProductId) {
                fetch(`${API_URL}?id=${deleteProductId}`, {
                    method: 'DELETE',
                    headers: getAuthHeaders()
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || (data.success ? 'Produit supprimé' : 'Erreur'));
                    deleteModal.hide();
                    loadProducts();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression');
                });
            }
        });

        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('product-form').reset();
            document.getElementById('product-id').value = '';
            document.getElementById('form-title').textContent = 'Ajouter un produit';
            document.getElementById('submit-text').textContent = 'Ajouter';
            document.getElementById('cancel-btn').style.display = 'none';
            
            // Réinitialiser l'onglet actif
            const basicTab = new bootstrap.Tab(document.getElementById('basic-tab'));
            basicTab.show();
        }

        // Gérer le changement de mot de passe
        document.getElementById('save-password-btn').addEventListener('click', function() {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const errorDiv = document.getElementById('password-error');
            
            errorDiv.style.display = 'none';
            
            if (newPassword.length < 6) {
                errorDiv.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
                errorDiv.style.display = 'block';
                return;
            }
            
            if (newPassword !== confirmPassword) {
                errorDiv.textContent = 'Les mots de passe ne correspondent pas';
                errorDiv.style.display = 'block';
                return;
            }
            
            fetch('api/change-password.php', {
                method: 'POST',
                headers: getAuthHeaders(),
                body: JSON.stringify({
                    currentPassword: 'admin123',
                    newPassword: newPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mot de passe modifié avec succès !');
                    const changePasswordModal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                    changePasswordModal.hide();
                } else {
                    errorDiv.textContent = data.message || 'Erreur lors du changement de mot de passe';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                errorDiv.textContent = 'Erreur de connexion au serveur';
                errorDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>
