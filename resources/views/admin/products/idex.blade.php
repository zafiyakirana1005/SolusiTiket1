@extends('admin.layouts.admin')

@section('title', 'Add Product - The Lodge Admin')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Create a new product')

@section('content')
<div class="container-fluid">
    <form id="productForm" action="#" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Main Product Info -->
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group-custom">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" class="form-control form-control-custom" 
                               name="name" placeholder="Enter product name" required>
                        <small class="text-muted">This will be used as the product title in the store.</small>
                    </div>

                    <!-- Slug -->
                    <div class="form-group-custom">
                        <label>Slug <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('/') }}/</span>
                            <input type="text" class="form-control form-control-custom" 
                                   name="slug" placeholder="product-slug" required>
                        </div>
                        <small class="text-muted">URL friendly version of the product name.</small>
                    </div>

                    <!-- Description -->
                    <div class="form-group-custom">
                        <label>Description</label>
                        <textarea class="form-control form-control-custom" 
                                  name="description" rows="5" 
                                  placeholder="Detailed product description..."></textarea>
                    </div>

                    <!-- SEO Metadata -->
                    <div class="form-group-custom">
                        <label>SEO Metadata</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-custom mb-2" 
                                       name="meta_title" placeholder="Meta Title">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-custom" 
                                       name="meta_description" placeholder="Meta Description">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants -->
                <div class="admin-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-palette me-2"></i>Variants & SKU</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addVariant()">
                            <i class="fas fa-plus"></i> Add Variant
                        </button>
                    </div>

                    <div id="variantsContainer">
                        <!-- Default Variant -->
                        <div class="variant-row" id="variant-0">
                            <button type="button" class="remove-variant" onclick="removeVariant(0)" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label small">SKU <span class="required">*</span></label>
                                    <input type="text" class="form-control form-control-custom" 
                                           name="variants[0][sku]" placeholder="SKU-001" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Price <span class="required">*</span></label>
                                    <input type="number" class="form-control form-control-custom" 
                                           name="variants[0][price]" placeholder="0.00" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Stock</label>
                                    <input type="number" class="form-control form-control-custom" 
                                           name="variants[0][stock]" placeholder="0" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Status</label>
                                    <select class="form-select form-select-sm" name="variants[0][status]">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label small">Attributes (JSON)</label>
                                    <input type="text" class="form-control form-control-custom" 
                                           name="variants[0][attributes]" 
                                           placeholder='{"size":"M","color":"Red"}' 
                                           value='{"size":"M","color":"Red"}'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 text-muted small">
                        <i class="fas fa-info-circle"></i> 
                        Each variant must have a unique SKU. Attributes help customers choose the right product.
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Product Image -->
                <div class="admin-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-image me-2"></i>Product Image</h5>
                    </div>
                    <div class="image-upload-area" id="imageUploadArea">
                        <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <h6>Drop image here or click to upload</h6>
                        <p class="text-muted small">Recommended: 800x800px, Max 2MB</p>
                        <input type="file" class="d-none" id="imageInput" accept="image/*" name="image">
                    </div>
                    <div id="imagePreview" class="mt-3 d-none">
                        <img src="" alt="Preview" class="image-preview" style="width:100%;max-height:200px;object-fit:cover;">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>

                <!-- Category & Status -->
                <div class="admin-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-tags me-2"></i>Category & Status</h5>
                    </div>

                    <div class="form-group-custom">
                        <label>Category <span class="required">*</span></label>
                        <select class="form-select form-control-custom" name="category_id" required>
                            <option value="">Select Category</option>
                            <option value="1">Accessories</option>
                            <option value="2">Clothing</option>
                            <option value="3">Merchandise</option>
                            <option value="4">Home & Living</option>
                            <option value="5">Electronics</option>
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label>Product Status</label>
                        <select class="form-select form-control-custom" name="status">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label>Featured Product</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                            <label class="form-check-label">Mark as featured</label>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory Summary -->
                <div class="admin-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-chart-line me-2"></i>Summary</h5>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-label">Total Variants</div>
                            <div class="stat-number" id="variantCount">1</div>
                        </div>
                        <div class="col-6">
                            <div class="stat-label">Total Stock</div>
                            <div class="stat-number" id="totalStock">0</div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="admin-card">
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-save me-2"></i> Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let variantCount = 1;

    // Image upload
    $('#imageUploadArea').on('click', function() {
        $('#imageInput').click();
    });

    $('#imageInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview img').attr('src', e.target.result);
                $('#imagePreview').removeClass('d-none');
                $('#imageUploadArea').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    function removeImage() {
        $('#imagePreview').addClass('d-none');
        $('#imageUploadArea').show();
        $('#imageInput').val('');
    }

    // Variant management
    function addVariant() {
        const container = $('#variantsContainer');
        const id = variantCount++;
        const html = `
            <div class="variant-row" id="variant-${id}">
                <button type="button" class="remove-variant" onclick="removeVariant(${id})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label small">SKU <span class="required">*</span></label>
                        <input type="text" class="form-control form-control-custom" 
                               name="variants[${id}][sku]" placeholder="SKU-${String(id+1).padStart(3, '0')}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Price <span class="required">*</span></label>
                        <input type="number" class="form-control form-control-custom" 
                               name="variants[${id}][price]" placeholder="0.00" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Stock</label>
                        <input type="number" class="form-control form-control-custom" 
                               name="variants[${id}][stock]" placeholder="0" value="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select class="form-select form-select-sm" name="variants[${id}][status]">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label small">Attributes (JSON)</label>
                        <input type="text" class="form-control form-control-custom" 
                               name="variants[${id}][attributes]" 
                               placeholder='{"size":"M","color":"Red"}'>
                    </div>
                </div>
            </div>
        `;
        container.append(html);
        updateSummary();
        
        // Show remove button for all variants if more than 1
        if (variantCount > 1) {
            $('.remove-variant').show();
        }
    }

    function removeVariant(id) {
        if (variantCount <= 1) {
            showToast('At least one variant is required', 'warning');
            return;
        }
        $(`#variant-${id}`).remove();
        variantCount--;
        updateSummary();
        
        if (variantCount <= 1) {
            $('.remove-variant').hide();
        }
    }

    function updateSummary() {
        let totalStock = 0;
        $('input[name*="[stock]"]').each(function() {
            const val = parseInt($(this).val()) || 0;
            totalStock += val;
        });
        $('#variantCount').text(variantCount);
        $('#totalStock').text(totalStock);
    }

    // Update stock summary on input change
    $(document).on('input', 'input[name*="[stock]"]', function() {
        updateSummary();
    });

    // Form submit
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate SKU uniqueness
        const skus = [];
        let isValid = true;
        $('input[name*="[sku]"]').each(function() {
            const sku = $(this).val();
            if (skus.includes(sku)) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                skus.push(sku);
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            showToast('Duplicate SKU found. Please use unique SKUs.', 'error');
            return;
        }

        // Simulate save
        const btn = $(this).find('button[type="submit"]');
        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...');
        btn.prop('disabled', true);

        setTimeout(function() {
            btn.html('<i class="fas fa-check me-2"></i> Product Saved!');
            btn.removeClass('btn-primary').addClass('btn-success');
            showToast('Product created successfully!', 'success');
            
            setTimeout(function() {
                btn.html('<i class="fas fa-save me-2"></i> Save Product');
                btn.prop('disabled', false);
                btn.removeClass('btn-success').addClass('btn-primary');
            }, 2000);
        }, 1500);
    });

    // Auto-generate slug from name
    $('input[name="name"]').on('keyup', function() {
        const slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('input[name="slug"]').val(slug);
    });
</script>
@endpush
@endsection