import React from 'react';
import { Trash2, Plus, Minus, Pencil } from 'lucide-react';
import './cart.css';

function Cart({ cartItems, onUpdate, onRemove, onClear, onPayment, paymentLoading }) {
    const total = cartItems.reduce(
    (acc, item) => acc + item.harga * item.qty,
    0
  );

  return (
    <div className="cart-wrapper">
      <div className="cart-header">
        <h3>Keranjang</h3>
        {cartItems.length > 0 && (
          <button className="clear-btn" onClick={onClear}>
            Bersihkan
          </button>
        )}
      </div>

      <div className="cart-items-list">
        {cartItems.length === 0 ? (
          <div className="empty-cart">Keranjang masih kosong</div>
        ) : (
          cartItems.map((item) => (
            <div key={item.id} className="cart-item">
              <div className="item-details">
                <h5>{item.nama}</h5>
                <p>Rp {item.harga.toLocaleString('id-ID')}  X <span>{item.qty}</span></p>
              </div>

              <div className="subtotal">
                <span>{(item.harga * item.qty).toLocaleString('id-ID')}</span>
              </div>
              <div className="item-controls">
                <button
                  className="edit-btn"
                  onClick={() => onUpdate(item.id)}
                >
                    <Pencil size={14} />
                </button>
                <button
                  className="delete-btn"
                  onClick={() => onRemove(item.id)}
                >
                  <Trash2 size={14} />
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      <div className="cart-footer">
        <div className="total-box">
          <span>Total Bayar</span>
          <h4>Rp {total.toLocaleString('id-ID')}</h4>
        </div>
        <button
          className="checkout-btn"
          disabled={cartItems.length === 0 || paymentLoading}
          onClick={onPayment}
        >
          {paymentLoading ? 'Memproses...' : 'Bayar Sekarang'}
        </button>
      </div>
    </div>
  );
}

export default Cart;