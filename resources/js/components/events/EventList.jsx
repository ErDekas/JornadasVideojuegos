import React, { useState, useEffect } from 'react';
import axios from 'axios';

const EventList = () => {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchEvents = async () => {
      try {
        const response = await axios.get('/api/events');
        setEvents(response.data);
      } catch (error) {
        console.error('Error fetching events:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchEvents();
  }, []);

  if (loading) return (
    <div className="d-flex justify-content-center">
      <div className="spinner-border" role="status">
        <span className="visually-hidden">Cargando...</span>
      </div>
    </div>
  );

  return (
    <div className="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      {events.map((event) => (
        <div key={event.id} className="col">
          <div className="card h-100">
            <div className="card-body">
              <h5 className="card-title">{event.title}</h5>
              <p className="card-text">{event.description}</p>
              <Link 
                to={`/events/${event.id}`}
                className="btn btn-primary"
              >
                Ver Detalles
              </Link>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
};

export default EventList;