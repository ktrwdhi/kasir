// import React, { useState } from 'react';
// import { authService } from '../../services/authService';
// import './login.css';

// function Login({ onLoginSuccess }) {
//   const [username, setUsername] = useState('');
//   const [password, setPassword] = useState('');
//   const [error, setError] = useState('');
//   const [isLoading, setIsLoading] = useState(false);

//   const handleSubmit = async (e) => {
//     e.preventDefault();
//     setError('');
//     setIsLoading(true);

//     try {
//       // 1. Panggil service login
//       const data = await authService.login(username, password);

//       // 2. Simpan token & data user ke localStorage
//       authService.saveSession(data.token, data.user);

//       // 3. Panggil callback jika login berhasil
//       if (onLoginSuccess) {
//         onLoginSuccess(data.user);
//       }
//     } catch (err) {
//       setError(err.message);
//     } finally {
//       setIsLoading(false);
//     }
//   };

//   return (
//     <div className="login-container">
//       <div className="login-card">
//         <h2>Login Kasir</h2>

//         {error && <p className="error-message">{error}</p>}

//         <form onSubmit={handleSubmit}>
//           <div className="form-group">
//             <label htmlFor="username">Username / Email</label>
//             <input
//               id="username"
//               type="text"
//               value={username}
//               onChange={(e) => setUsername(e.target.value)}
//               placeholder="Masukkan username"
//               required
//             />
//           </div>

//           <div className="form-group">
//             <label htmlFor="password">Password</label>
//             <input
//               id="password"
//               type="password"
//               value={password}
//               onChange={(e) => setPassword(e.target.value)}
//               placeholder="Masukkan password"
//               required
//             />
//           </div>

//           <button type="submit" disabled={isLoading}>
//             {isLoading ? 'Memproses...' : 'Masuk'}
//           </button>
//         </form>
//       </div>
//     </div>
//   );
// }

// export default Login;

import React ,{useState} from 'react';
import {Login as LoginService} from '../../services/authService';
import './login.css';
import { useNavigate } from 'react-router-dom';

function Login({onLoginSuccess}){
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [isLoading, setIsLoading] = useState(false)
    const navigate = useNavigate();

    const handleSubmit = async (e)=> {
        e.preventDefault();
        setError('');
        setIsLoading(true);

        try{
            const data = await LoginService(username,password);
            localStorage.setItem('token',data.access_token);
            if(onLoginSuccess){
                onLoginSuccess(data.user);
            }
            navigate('/Dashboard')
        }catch(err){
            setError(err.message);
        }finally{
            setIsLoading(false);
        }
    }

    return (
        <div className="login-container">
            <div className="login-card">
                <h2>Login Kasir</h2>
                {error && <p className="error-message">{error}</p>}
                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label htmlFor="username">Username</label>
                        <input
                            id='username' 
                            type="text"
                            value={username}
                            onChange={(e) => setUsername(e.target.value)}
                            placeholder='Masukan username'
                         />
                    </div>
                    <div className="form-group">
                        <label htmlFor="password">Password</label>
                        <input
                            id='password' 
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder='Masukan password'
                         />
                    </div>
                    <button type="submit" disabled={isLoading}>
                        {isLoading ? "Memproses" : "Masuk"}
                    </button>
                </form>
            </div>
        </div>
    )
}
export default Login;