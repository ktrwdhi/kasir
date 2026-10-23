import axios from "axios";

const API_URL = "http://localhost:8000/api";

export const createSale = async (items, paymentMethod,cash,change) => {
    const token = localStorage.getItem("token");

    const response = await axios.post(
        `${API_URL}/sales`,
        {
            items: items.map(item => ({
                id: item.id,
                qty: item.qty
            })),

            payment: paymentMethod,
            cash: cash,
            change: change
        },
        {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json"
            }
        }
    );

    return response.data;
};