const API_URL = 'http://localhost:8000/api';

export async function CreateQris(items) {
    const token = localStorage.getItem('token');

    const response = await fetch(`${API_URL}/payment/qris`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            items: items
        })
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'Gagal membuat QRIS');
    }

    return data;
}