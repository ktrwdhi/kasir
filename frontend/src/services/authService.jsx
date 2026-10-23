
const API = "http://localhost:8000/api/";

async function Login(username, password) {
    const response = await fetch(`${API}login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ username, password })
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'Login gagal');
    }

    localStorage.setItem('token', data.access_token);
    localStorage.setItem('name', data.user.name);
    localStorage.setItem('role', data.user.role);

    return data;
}


function IsLoggedIn() {
    return !!localStorage.getItem('token');
}

function Logout() {
    localStorage.clear();
}

export { Login, IsLoggedIn, Logout };
