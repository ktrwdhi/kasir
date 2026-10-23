import React from 'react';
import {Category} from '../../services/productService'
import './sidebar.css';
import { LogOut } from 'lucide-react';

function Sidebar({ selectedCategory, onSelectCategory, categories, onLogout }) {
  return (
    <aside className="sidebar-component">
      {/* Brand / Logo App */}
      <div className="brand">
        <div className="brand-badge">⚡</div>
        <h2>Kasir<span>App</span></h2>
      </div>

      {/* Menu Kategori */}
      <div className="category-section">
        <span className="section-title">KATEGORI</span>
        <ul className="category-list">
          <li
                  className={`category-item ${
                      selectedCategory === "Semua" ? "active" : ""
                  }`}
                  onClick={() => onSelectCategory("Semua")}
              >
                  <span>Semua</span>
          </li>          
          {categories.map((cat) => {
            const isActive = selectedCategory === cat.id;
            return (
              <li
                key={cat.id}
                className={`category-item ${isActive ? 'active' : ''}`}
                onClick={() => onSelectCategory(cat.id)}
              >
                <span>{cat.name.charAt(0).toUpperCase() + cat.name.slice(1)}</span>
              </li>
            );
          })}
        </ul>
      </div>

      <div className="sidebar-footer">
        <button className="logout-btn" onClick={onLogout}>
          <LogOut size={18} />
          <span>Keluar</span>
        </button>
      </div>
    </aside>
  );
}

export default Sidebar;