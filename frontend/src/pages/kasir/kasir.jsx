import React,{useState, useEffect} from 'react';
import Header from '../../components/header/header';
import { useNavigate } from 'react-router-dom';
import { IsLoggedIn } from '../../services/authService';
import './kasir.css'
import Sidebar from '../../components/sidebar/sidebar';
import Menu from '../../components/menu/menu';
import Cart from '../../components/cart/cart'
import Modal from '../../components/modal/modal';
import {Category, Product} from '../../services/productService';
import { CreateQris } from '../../services/paymentService';
import PaymentModal from "../../components/payment/payment";
import { createSale } from '../../services/transactionService';

function Kasir({onLogout}){
    const [user, setUser] = useState("");
    const [role, setRole] = useState("");
    const [category, setCategory] = useState("Semua");
    const [categories, setCategories] = useState([]);
    const [error, setError] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [products,setProduct] = useState([]);
    const navigate = useNavigate()
    const isLogged = IsLoggedIn();
    const [showModal, setShowModal] = useState(false);
    const [selectedProductId, setSelectedProductId] = useState(null);
    const [cart, setCart] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);
    const [showPayment, setShowPayment] = useState(false);
    const [qris, setQris] = useState('');
    const [paymentAmount, setPaymentAmount] = useState(0);
    const [paymentLoading, setPaymentLoading] = useState(false);
    const [showPaymentOption, setShowPaymentOption] = useState(false);
    const [paymentMethod, setPaymentMethod] = useState('');
    const [isCheckout, setIsCheckout] = useState(false);


    
    useEffect(() => {
        if (!isLogged) {
            navigate('/login');
            return;
        }

        const name = localStorage.getItem('name');
        const userRole = localStorage.getItem('role');

        setUser(name || 'no name');
        setRole(userRole || '');

        async function getData() {
            try {
                const [productData, categoryData] = await Promise.all([
                    Product(),
                    Category()
                ]);
                
                if (Array.isArray(productData)) {
                    setProduct(productData);
                } else if (Array.isArray(productData?.data)) {
                    setProduct(productData.data);
                } else {
                    setProduct([]);
                }

                if (Array.isArray(categoryData)) {
                    setCategories(categoryData);
                } else if (Array.isArray(categoryData?.data)) {
                    setCategories(categoryData.data);
                } else {
                    setCategories([]);
                }
            } catch (error) {
                console.error(error.message);
            } finally {
                setIsLoading(false);
            }
        }

        getData();
    }, [isLogged, navigate]);

    useEffect(() => {
        if (category === "Semua") {
            setFilteredProducts(products);
            return;
        }

        const filtered = products.filter(
            product => String(product.category_id) === String(category)
        );

        setFilteredProducts(filtered);

    }, [products, category]);

    const handleCart = (productId, quantity) => {
        setCart(prevCart => {
            const existingProduct = prevCart.find(
                item => item.product_id === productId
            );

            if (existingProduct) {
                return prevCart.map(item =>
                    item.product_id === productId
                        ? {
                            ...item,
                            quantity: item.quantity + quantity
                        }
                        : item
                );
            }

            return [
                ...prevCart,
                {
                    product_id: productId,
                    quantity: quantity
                }
            ];
        });

        closeModal();
    };

    const handleRemoveItem = (productId) => {
        setCart(prevCart =>
            prevCart.filter(item => item.product_id !== productId)
        );
    };

    const handleClearCart = () => {
        setCart([]);
    };

    const openModal = (id) => {
        setSelectedProductId(id);
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setSelectedProductId(null);
    };

    const handleProductClick = (id) => {
        setSelectedProductId(id);
        setShowModal(true);
    };

    const handlePayment = () => {
        if (cartItems.length === 0) {
            alert('Keranjang masih kosong');
            return;
        }

        setShowPaymentOption(true);
    };

    const handleQrisPayment = async () => {
        try {
            setPaymentLoading(true);

            const data = await CreateQris(
                cartItems.map(item => ({
                    id: item.id,
                    qty: item.qty
                }))
            );

            setQris(data.qris);
            setPaymentAmount(data.amount);
            setPaymentMethod('qris');

            setShowPaymentOption(false);
            setShowPayment(true);

        } catch (error) {
            console.error(error);
            alert(error.message);
        } finally {
            setPaymentLoading(false);
        }
    };

    const handleCashPayment = () => {
        const total = cartItems.reduce(
            (total, item) => total + (item.harga * item.qty),
            0
        );

        setPaymentAmount(total);
        setPaymentMethod('cash');

        setShowPaymentOption(false);
        setShowPayment(true);
    }


    const handleLogout = () => {
        const konfirmasi = confirm('Are you sure to want logout?')
        if(konfirmasi){
            var logout = localStorage.clear();
            navigate('/login',{replace: true});
            if(onLogout){
                onLogout()
            }
        }
    }

    const cartItems = cart.map(cartItem => {
        const productData = products.find(
            item => item.id === cartItem.product_id
        );

        return {
            id: productData.id,
            nama: productData.name,
            harga: productData.price,
            qty: cartItem.quantity
        };
    });

    const selectedProduct = products.find(item => item.id === selectedProductId) || null;

    const handleCheckout = async (cash, change) => {
        if (cartItems.length === 0 || isCheckout) {
            return;
        }

        setIsCheckout(true);

        try {
            await createSale(
                cartItems,
                paymentMethod,
                cash,
                change
            );

            setProduct(prevProducts =>
                prevProducts.map(product => {
                    const item = cartItems.find(
                        item => Number(item.id) === Number(product.id)
                    );

                    if (!item) return product;

                    return {
                        ...product,
                        stock: Number(product.stock) - Number(item.qty)
                    };
                })
            );

            setCart([]);
            setShowPayment(false);

        } catch (error) {
            console.error("Checkout error:", error);

            alert(
                error.response?.data?.message ||
                error.message ||
                "Transaksi gagal."
            );
        } finally {
            setIsCheckout(false);
        }
    };
    return(
        <div className="kasir">
            <Sidebar
                selectedCategory = {category}
                onSelectCategory = {setCategory}
                categories = {categories}
                onLogout={handleLogout}
            />
            <div className="main-wrapper">
                <Header
                    // cartItems={cartItems}
                    userRole={role}
                    userName={user}
                />
                <div className="content-area">
                <main className="menu-container">
                    {isLoading ? (
                    <p className="loading-text">Memuat data produk...</p>
                    ) : error ? (
                    <p className="error-text">Terjadi kesalahan: {error}</p>
                    ) : (
                    <Menu
                        products={filteredProducts}
                        onProductClick={handleProductClick}
                        />
                    )}
                    {showModal && (
                        <Modal
                            productId={selectedProductId}
                            product={selectedProduct}
                            onAddToCart={handleCart}
                            closeModal={closeModal}
                        />
                    )}
                </main>

                <aside className="cart-container">
                    <Cart
                        cartItems={cartItems}
                        onRemove={handleRemoveItem}
                        onClear={handleClearCart}
                        onPayment={handlePayment}
                        paymentLoading={paymentLoading}
                    />
                </aside> 
                </div>
                {showPaymentOption && (
                    <div
                        className="payment-option-overlay"
                        onClick={() => setShowPaymentOption(false)}
                    >
                        <div
                            className="payment-option-modal"
                            onClick={(e) => e.stopPropagation()}
                        >

                            <div className="payment-option-header">
                                <h2>Pilih Pembayaran</h2>
                                <p>
                                    Silakan pilih metode pembayaran
                                </p>
                            </div>

                            <div className="payment-options">

                                <button
                                    className="payment-option cash"
                                    onClick={handleCashPayment}
                                >
                                    <div className="payment-option-icon">
                                        💵
                                    </div>

                                    <div className="payment-option-info">
                                        <h3>Cash</h3>
                                        <p>Bayar menggunakan uang tunai</p>
                                    </div>
                                </button>


                                <button
                                    className="payment-option qris"
                                    onClick={handleQrisPayment}
                                    disabled={paymentLoading}
                                >
                                    <div className="payment-option-icon">
                                        📱
                                    </div>

                                    <div className="payment-option-info">
                                        <h3>QRIS</h3>
                                        <p>Bayar menggunakan QRIS</p>
                                    </div>
                                </button>

                            </div>

                            <button
                                className="payment-option-close"
                                onClick={() => setShowPaymentOption(false)}
                            >
                                Batal
                            </button>

                        </div>
                    </div>
                )}
                
                <PaymentModal
                    showPayment={showPayment}
                    setShowPayment={setShowPayment}
                    paymentAmount={paymentAmount}
                    qris={qris}
                    paymentMethod={paymentMethod}                        
                    onClear={handleClearCart}
                    transaction={handleCheckout}
                />
            </div>
        </div>
    )
}
export default Kasir;