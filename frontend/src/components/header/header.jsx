// import './header.css';
// import { ShoppingCart, LogOut } from 'lucide-react';

// function Header(){
//     return(
//         <div className="header-component">
//             <div className="shop-name">
//                 <h1>Kasir App</h1>
//             </div>
//             <div className="search">
//                 <input type="text" placeholder="Search product..."/>
//             </div>

//             <div className="info">
//                 <div className="greeting">
//                     <p>Selamat Pagi, Katiar!</p>
//                 </div>
//                 <div className="icon">
//                     <div className="cart-icon">
//                         <ShoppingCart/>
//                     </div>
//                     <div className="logout-icon">
//                         <LogOut/>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     );
// }

// export default Header;

import React from 'react';
import { Search, ShoppingCart, LogOut } from 'lucide-react';
import './Header.css';

function Header({ searchQuery, onSearchChange, cartCount, userName, userRole }) {
  return (
    <header className="header-component">
      <div className="search-bar">
        <Search size={18} className="search-icon" />
        <input
          type="text"
          placeholder="Cari menu atau minuman..."
          value={searchQuery}
          onChange={(e) => onSearchChange(e.target.value)}
        />
      </div>

      <div className="header-right">
        <div className="user-greeting">
          <p>{userName} - <strong>{userRole}</strong></p>
        </div>

        <div className="cart-badge-container">
          <button className="icon-btn">
            <ShoppingCart size={20} />
            {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
          </button>
        </div>

        {/* <button className="icon-btn logout-btn" title="Keluar" onClick={onLogout}>
          <LogOut size={20} />
        </button> */}
      </div>
    </header>
  );
}

export default Header;