import {
    BrowserRouter,
    Routes,
    Route,
    Navigate
} from 'react-router-dom';

import { useState } from 'react';

import { IsLoggedIn } from './services/authService';

import LoginPage from './pages/login/login';
import Dashboard from './pages/kasir/kasir';
import NotFound from './pages/notfound/notfound';


function App() {
    const [isLoggedIn, setIsLoggedIn] = useState(IsLoggedIn());

    return (
        <BrowserRouter>

            <Routes>

                <Route
                    path="/"
                    element={
                        isLoggedIn
                            ? <Navigate to="/Dashboard" replace />
                            : <Navigate to="/login" replace />
                    }
                />

                <Route
                    path="/login"
                    element={
                        isLoggedIn
                            ? <Navigate to="/Dashboard" replace />
                            : (
                                <LoginPage
                                    onLoginSuccess={() => setIsLoggedIn(true)}
                                />
                            )
                    }
                />

                <Route
                    path="/Dashboard"
                    element={
                        isLoggedIn
                            ? (
                                <Dashboard
                                    onLogout={() => setIsLoggedIn(false)}
                                />
                            )
                            : <Navigate to="/login" replace />
                    }
                />

                <Route
                    path="*"
                    element={<NotFound />}
                />

            </Routes>

        </BrowserRouter>
    );
}

export default App;