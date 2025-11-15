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
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><i class="bi bi-box-seam"></i> Gestion des Produits</h1>
            </div>
        </div>

        <!-- Formulaire d'ajout/modification -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="form-section">
                    <h3 id="form-title" class="mb-3">Ajouter un produit</h3>
                    <form id="product-form">
                        <input type="hidden" id="product-id" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom du produit *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Catégorie *</label>
                                <input type="text" class="form-control" id="category" name="category" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="price" class="form-label">Prix (€) *</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="original-price" class="form-label">Prix original (€)</label>
                                <input type="number" step="0.01" class="form-control" id="original-price" name="originalPrice">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="discount" class="form-label">Remise (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="badge" class="form-label">Badge</label>
                                <select class="form-select" id="badge" name="badge">
                                    <option value="">Aucun</option>
                                    <option value="promo">Promo</option>
                                    <option value="best-seller">Best-seller</option>
                                    <option value="new">Nouveau</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="rating" class="form-label">Note (0-5) *</label>
                                <input type="number" step="0.1" min="0" max="5" class="form-control" id="rating" name="rating" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="reviews" class="form-label">Nombre d'avis *</label>
                                <input type="number" class="form-control" id="reviews" name="reviews" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
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
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Note</th>
                                        <th>Stock</th>
                                        <th>Badge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table">
                                    <tr>
                                        <td colspan="8" class="text-center">
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
        let deleteProductId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Charger les produits au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });

        // Gérer la soumission du formulaire
        document.getElementById('product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                if (value !== '') {
                    data[key] = value;
                }
            });

            const productId = document.getElementById('product-id').value;
            const method = productId ? 'PUT' : 'POST';
            const url = productId ? `${API_URL}/${productId}` : API_URL;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    resetForm();
                    loadProducts();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        });

        // Charger tous les produits
        function loadProducts() {
            fetch(API_URL)
                .then(response => response.json())
                .then(products => {
                    const tbody = document.getElementById('products-table');
                    if (products.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center">Aucun produit trouvé</td></tr>';
                        return;
                    }

                    tbody.innerHTML = products.map(product => `
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td><span class="badge bg-info">${product.category}</span></td>
                            <td>
                                ${product.originalPrice ? `<span class="text-decoration-line-through text-muted">${product.originalPrice}€</span> ` : ''}
                                <strong>${product.price}€</strong>
                                ${product.discount ? `<span class="badge bg-danger">-${product.discount}%</span>` : ''}
                            </td>
                            <td>
                                <i class="bi bi-star-fill text-warning"></i> ${product.rating}
                                <small class="text-muted">(${product.reviews})</small>
                            </td>
                            <td>
                                <span class="badge ${product.stock > 50 ? 'bg-success' : product.stock > 10 ? 'bg-warning' : 'bg-danger'}">
                                    ${product.stock}
                                </span>
                            </td>
                            <td>
                                ${product.badge ? `<span class="badge bg-primary badge-custom">${product.badge}</span>` : '-'}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(${product.id})" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('products-table').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger">Erreur lors du chargement des produits</td></tr>';
                });
        }

        // Modifier un produit
        function editProduct(id) {
            fetch(`${API_URL}/${id}`)
                .then(response => response.json())
                .then(product => {
                    document.getElementById('product-id').value = product.id;
                    document.getElementById('name').value = product.name;
                    document.getElementById('category').value = product.category;
                    document.getElementById('description').value = product.description || '';
                    document.getElementById('price').value = product.price;
                    document.getElementById('original-price').value = product.originalPrice || '';
                    document.getElementById('discount').value = product.discount || '';
                    document.getElementById('rating').value = product.rating;
                    document.getElementById('reviews').value = product.reviews;
                    document.getElementById('stock').value = product.stock;
                    document.getElementById('badge').value = product.badge || '';

                    document.getElementById('form-title').textContent = 'Modifier le produit';
                    document.getElementById('submit-text').textContent = 'Modifier';
                    document.getElementById('cancel-btn').style.display = 'inline-block';

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
                fetch(`${API_URL}/${deleteProductId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
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
        }
    </script>
</body>
</html>

