import React from 'react';
import { Plus } from 'lucide-react';
import './Menu.css';

function Menu({ products, onProductClick }) {
    if (products.length === 0) {
        return (
            <div className="no-products">
                Produk tidak ditemukan.
            </div>
        );
    }

    return (
        <div className="menu-grid">
            {products.map((item) => (

                    <div key={item.id} className="menu-card">
                        <div className="menu-image">
                            <img
                                src={item.image}
                                alt={item.name}
                            />

                            <span className="category-tag">
                                {item.category_id}
                            </span>
                        </div>

                        <div className="menu-info">
                            <h4>{item.name}</h4>

                            <p className="menu-price">
                                Rp {Number(item.price).toLocaleString('id-ID')}
                            </p>

                            <button
                                className="add-btn"
                                type="button"
                                onClick={() => onProductClick(item.id)}
                            >
                                <Plus size={16} />
                                Tambah
                            </button>
                        </div>
                    </div>
                )
            )}
        </div>
    );
}

export default Menu;