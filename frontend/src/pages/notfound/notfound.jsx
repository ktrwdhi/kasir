
import { useNavigate } from 'react-router-dom';
import './notfound.css';

function NotFound() {
    const navigate = useNavigate();

    return (
        <div className="notfound">
            <div className="notfound-content">
                <div className="notfound-illustration">
                    <div className="notfound-number">404</div>
                    <div className="notfound-emoji">🤔</div>
                </div>

                <div className="notfound-text">
                    <span className="notfound-label">
                        ERROR 404
                    </span>

                    <h1>Page Not Found</h1>

                    <p>
                        Maaf, halaman yang kamu cari tidak dapat
                        ditemukan. Mungkin URL yang kamu masukkan
                        salah atau halaman sudah tidak tersedia.
                    </p>

                    <button
                        className="notfound-button"
                        onClick={() => navigate(-1)}
                    >
                        <span>⌂</span>
                        Kembali ke Halaman Sebelumnya
                    </button>

                    <div className="notfound-footer">
                        Kasir App · Halaman tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    );
}

export default NotFound;
