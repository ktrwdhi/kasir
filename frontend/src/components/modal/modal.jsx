import React, { use, useEffect, useState } from 'react';
import { Product } from '../../services/productService';
import './modal.css';

function Modal({ productId, product, onAddToCart, closeModal }) {
    const [quantity, setQuantity] = useState(1);
    const [stock, setStock] = useState(1);

    useEffect(() => {
        const fetchStock = async () => {
            try {
                const response = await Product();

                const products = Array.isArray(response)
                    ? response
                    : response?.data ?? [];

                const latestProduct = products.find(
                    item => String(item.id) === String(productId)
                );

                if (latestProduct) {
                    setStock(Number(latestProduct.stock ?? 0));
                }
            } catch (error) {
                console.error('Gagal mengambil stok terbaru:', error);
            }
        };

        if (productId) {
            fetchStock();
        }
    }, [productId]);

    if (!product) return null;

    const updateQuantity = (type) => {
        if (type === "up") {
            setQuantity(prev => prev + 1);
        } else if (type === "down") {
            setQuantity(prev => (prev > 1 ? prev - 1 : 1));
        }
    };

    const handleQuantityChange = (e) => {
        const val = Number(e.target.value);
        if (val >= 1) setQuantity(val);
    };

    return (
        <div className="modal-overlay" onClick={closeModal}>
            <div className="modal-box" onClick={(e) => e.stopPropagation()}>
                <div className="product">
                    <div className="image">
                        <img src={product.image || "/placeholder.png"} alt={product.name} />
                    </div>

                    <div className="name">
                        <p>{product.name}</p>
                    </div>

                    <div className="stock">
                        Stok: {product.stock ?? 0}
                        {Number(product.stock) <= 0 && " (Habis)"}
                    </div>

                    <div className="quantity">
                        <div className="downQty">
                            <button onClick={() => updateQuantity("down")}>-</button>
                        </div>

                        <div className="input">
                            <input
                                type="number"
                                name="quantity"
                                min={1}
                                value={quantity}
                                onChange={handleQuantityChange}
                            />
                        </div>

                        <div className="upQty">
                            <button onClick={() => updateQuantity("up")}>+</button>
                        </div>
                    </div>

                    <div className="action">
                        <button onClick={() => onAddToCart(product.id, quantity)}>Tambah</button>
                        <button onClick={closeModal}>Batal</button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Modal;