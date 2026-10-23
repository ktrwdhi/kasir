
const api = "http://localhost:8000/api";

async function Product() {
    const token = localStorage.getItem('token');

    if (!token) {
        throw new Error('Token tidak ditemukan. Silakan login kembali.');
    }

    const response = await fetch(`${api}/products`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || `HTTP Error: ${response.status}`);
    }

    return data;
}

async function Category() {
    const token = localStorage.getItem('token');

    if (!token) {
        throw new Error('Token tidak ditemukan. Silakan login kembali.');
    }

    const response = await fetch(`${api}/category`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || `HTTP Error: ${response.status}`);
    }

    return data;
}

export {Product, Category};
