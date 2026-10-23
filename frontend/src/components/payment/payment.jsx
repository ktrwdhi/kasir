import { useEffect, useState, useRef } from "react";
import "./payment.css";

function PaymentModal({
    showPayment,
    setShowPayment,
    paymentAmount,
    qris,
    paymentMethod,
    onClear,
    transaction
}) {
    const [paymentSuccess, setPaymentSuccess] = useState(false);
    const [cashReceived, setCashReceived] = useState("");
    const transactionLock = useRef(false);
    const [isProcessing, setIsProcessing] = useState(false);

    useEffect(() => {
        if (showPayment) {
            setPaymentSuccess(false);
            setCashReceived("");
        }
    }, [showPayment]);

    if (!showPayment) {
        return null;
    }

    const cashValue = Number(cashReceived) || 0;

    const changeAmount = Math.max(
        cashValue - Number(paymentAmount),
        0
    );

    const isCashEnough =
        cashValue >= Number(paymentAmount);

    const handleSuccess = () => {
        setPaymentSuccess(true);
    };

    const handleClose = () => {
        setPaymentSuccess(false);
        setCashReceived("");
        setShowPayment(false);
        onClear();
    };

    const handleTransaction = async () => {
        if (transactionLock.current) return;

        transactionLock.current = true;
        setIsProcessing(true);

        try {
            await transaction(cashValue, changeAmount);
        } catch (error) {
            console.error("Transaksi gagal:", error);
        } finally {
            transactionLock.current = false;
            setIsProcessing(false);
        }
    };

    const handlePrintReceipt = () => {
        const receiptWindow = window.open(
            "",
            "_blank",
            "width=400,height=600"
        );

        if (!receiptWindow) {
            alert("Popup diblokir oleh browser.");
            return;
        }

        const date = new Date().toLocaleString("id-ID");

        receiptWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk Pembayaran</title>

                <style>
                    * {
                        box-sizing: border-box;
                    }

                    body {
                        margin: 0;
                        padding: 20px;

                        font-family: Arial, sans-serif;

                        color: #000;

                        background: #fff;
                    }

                    .receipt {
                        width: 280px;
                        margin: 0 auto;

                        text-align: center;
                    }

                    .store-name {
                        font-size: 20px;
                        font-weight: bold;

                        margin-bottom: 5px;
                    }

                    .store-address {
                        font-size: 11px;

                        margin-bottom: 15px;
                    }

                    .line {
                        border-top: 1px dashed #000;

                        margin: 10px 0;
                    }

                    .transaction {
                        text-align: left;

                        font-size: 12px;

                        line-height: 1.6;
                    }

                    .payment {
                        margin-top: 10px;

                        text-align: left;

                        font-size: 12px;

                        line-height: 1.7;
                    }

                    .payment-row {
                        display: flex;

                        justify-content: space-between;
                    }

                    .total {
                        font-size: 16px;

                        font-weight: bold;
                    }

                    .footer {
                        margin-top: 20px;

                        font-size: 11px;
                    }

                    @media print {
                        body {
                            padding: 0;
                        }

                        .receipt {
                            width: 280px;
                        }
                    }
                </style>
            </head>

            <body>

                <div class="receipt">

                    <div class="store-name">
                        KASIR APP
                    </div>

                    <div class="store-address">
                        Terima kasih telah berbelanja
                    </div>

                    <div class="line"></div>

                    <div class="transaction">
                        <div>
                            Tanggal: ${date}
                        </div>

                        <div>
                            Metode: ${
                                paymentMethod === "cash"
                                    ? "Cash"
                                    : "QRIS"
                            }
                        </div>
                    </div>

                    <div class="line"></div>

                    <div class="payment">

                        <div class="payment-row">
                            <span>Total</span>

                            <span>
                                Rp ${Number(paymentAmount).toLocaleString("id-ID")}
                            </span>
                        </div>

                        ${
                            paymentMethod === "cash"
                                ? `
                                    <div class="payment-row">
                                        <span>Dibayar</span>

                                        <span>
                                            Rp ${cashValue.toLocaleString("id-ID")}
                                        </span>
                                    </div>

                                    <div class="payment-row">
                                        <span>Kembalian</span>

                                        <span>
                                            Rp ${changeAmount.toLocaleString("id-ID")}
                                        </span>
                                    </div>
                                `
                                : ""
                        }

                        <div class="line"></div>

                        <div class="payment-row total">
                            <span>TOTAL</span>

                            <span>
                                Rp ${Number(paymentAmount).toLocaleString("id-ID")}
                            </span>
                        </div>

                    </div>

                    <div class="footer">
                        Pembayaran berhasil
                        <br />
                        Terima kasih 🙏
                    </div>

                </div>

                <script>
                    window.onload = function() {
                        window.print();
                    };
                </script>

            </body>
            </html>
        `);

        receiptWindow.document.close();
    };

    return (
        <div
            className="qris-overlay"
            onClick={handleClose}
        >
            <div
                className="qris-modal"
                onClick={(e) => e.stopPropagation()}
            >

                <div className="qris-body">

                    {paymentSuccess ? (
                        <div className="qris-success-container">
                            <div className="success-circle">
                                <svg
                                    className="success-check"
                                    viewBox="0 0 52 52"
                                >
                                    <circle
                                        className="success-circle-bg"
                                        cx="26"
                                        cy="26"
                                        r="25"
                                    />
                                    <path
                                        className="success-check-path"
                                        fill="none"
                                        d="M14 27l7 7 17-17"
                                    />
                                </svg>
                            </div>
                            <h2>
                                Pembayaran Berhasil!
                            </h2>
                            <p>
                                Pembayaran sebesar
                            </p>
                            <h1>
                                Rp {Number(paymentAmount).toLocaleString("id-ID")}
                            </h1>
                            <p className="success-message">
                                Transaksi kamu telah berhasil diproses.
                            </p>

                            {paymentMethod === "cash" && (
                                <div className="success-change">
                                    <span>
                                        Kembalian
                                    </span>
                                    <strong>
                                        Rp {changeAmount.toLocaleString("id-ID")}
                                    </strong>
                                </div>
                            )}
                            <button
                                className="print-receipt-button"
                                onClick={handlePrintReceipt}
                            >
                                🖨️ Cetak Struk
                            </button>
                        </div>
                    ) : (
                        <>
                            {paymentMethod === "qris" ? (
                                <>
                                    <div className="qris-total">
                                        <span>
                                            Total Pembayaran
                                        </span>
                                        <h1>
                                            Rp {Number(paymentAmount).toLocaleString("id-ID")}
                                        </h1>
                                    </div>
                                    <div className="qris-wrapper">
                                        <div className="qris-frame">
                                            <img
                                                src={`https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qris)}`}
                                                alt="QRIS Code"
                                                className="qris-image"
                                            />
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <div className="cash-payment">
                                    <div className="cash-payment-header">
                                        <h2>
                                            Pembayaran Cash
                                        </h2>
                                        <p>
                                            Masukkan jumlah uang yang diterima
                                        </p>
                                    </div>

                                    <div className="cash-payment-total">
                                        <span>
                                            Total Pembayaran
                                        </span>

                                        <h1>
                                            Rp {Number(paymentAmount).toLocaleString("id-ID")}
                                        </h1>
                                    </div>

                                    <div className="cash-input-group">
                                        <label>
                                            Uang Dibayar
                                        </label>

                                        <div className="cash-input-wrapper">
                                            <span>Rp</span>

                                            <input
                                                type="text"
                                                inputMode="numeric"
                                                value={cashReceived}
                                                onChange={(e) => {
                                                    const angka = e.target.value.replace(/\D/g, "");

                                                    setCashReceived(
                                                        angka === ""
                                                            ? ""
                                                            : Number(angka).toLocaleString("id-ID")
                                                    );
                                                }}
                                                placeholder="0"
                                            />
                                        </div>
                                    </div>
                                    <div className="cash-change">
                                        <span>
                                            Kembalian
                                        </span>

                                        <strong>
                                            Rp {changeAmount.toLocaleString("id-ID")}
                                        </strong>
                                    </div>

                                    {cashReceived !== "" &&
                                        !isCashEnough && (
                                            <p className="cash-warning">
                                                Uang yang dibayar masih kurang
                                                <br />
                                                Kurang Rp {
                                                    (
                                                        Number(paymentAmount) -
                                                        cashValue
                                                    ).toLocaleString("id-ID")
                                                }
                                            </p>
                                        )
                                    }
                                </div>
                            )}
                        </>
                    )}

                </div>

                <div className="qris-footer">
                    {!paymentSuccess ? (
                        <>
                            {paymentMethod === "cash" ? (
                                <button
                                    className="qris-success"
                                    disabled={!isCashEnough}
                                    onClick={handleSuccess}
                                >
                                    Bayar
                                </button>
                            ) : (
                                <button
                                    className="qris-success"
                                    onClick={handleSuccess}
                                >
                                    Selesai
                                </button>
                            )}
                            <button
                                className="qris-cancel"
                                onClick={handleClose}
                            >
                                Tutup
                            </button>
                        </>
                    ) : (
                        <button
                            className="qris-success"
                            onClick={handleTransaction}
                            disabled={isProcessing}
                        >
                            {isProcessing ? "Memproses..." : "Selesai"}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
}

export default PaymentModal;