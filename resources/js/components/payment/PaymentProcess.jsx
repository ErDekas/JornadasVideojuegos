import React from 'react';
import { useParams } from 'react-router-dom';

const PaymentProcess = () => {
  const { registration } = useParams();

  const initiatePayPalPayment = async () => {
    try {
      const response = await axios.get('/api/paypal/pay');
      window.location.href = response.data.redirect_url;
    } catch (error) {
      console.error('Error initiating PayPal payment:', error);
    }
  };

  return (
    <div className="container py-4">
      <div className="card">
        <div className="card-body">
          <h2 className="card-title">Proceso de Pago</h2>
          <div className="mt-4">
            <button
              onClick={initiatePayPalPayment}
              className="btn btn-primary w-100"
            >
              Pagar con PayPal
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PaymentProcess;