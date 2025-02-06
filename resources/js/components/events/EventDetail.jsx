import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const EventDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [event, setEvent] = useState(null);

  useEffect(() => {
    const fetchEvent = async () => {
      try {
        const response = await axios.get(`/api/events/${id}`);
        setEvent(response.data);
      } catch (error) {
        console.error('Error fetching event:', error);
      }
    };

    fetchEvent();
  }, [id]);

  const handleRegister = () => {
    navigate(`/events/${id}/register`);
  };

  if (!event) return (
    <div className="text-center">
      <div className="spinner-border" role="status">
        <span className="visually-hidden">Cargando...</span>
      </div>
    </div>
  );

  return (
    <div className="container py-4">
      <div className="card">
        <div className="card-body">
          <h1 className="card-title h3">{event.title}</h1>
          <p className="card-text">{event.description}</p>
          <button
            onClick={handleRegister}
            className="btn btn-primary"
          >
            Registrarse al Evento
          </button>
        </div>
      </div>
    </div>
  );
};

export default EventDetail;