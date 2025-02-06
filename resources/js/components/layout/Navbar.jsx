import React from 'react';
import { Link, useNavigate } from 'react-router-dom';

const Navbar = ({ isAuthenticated }) => {
  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <div className="container">
        <Link to="/" className="navbar-brand">EventApp</Link>
        <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarNav">
          <ul className="navbar-nav me-auto">
            <li className="nav-item">
              <Link to="/events" className="nav-link">Eventos</Link>
            </li>
            <li className="nav-item">
              <Link to="/speakers" className="nav-link">Ponentes</Link>
            </li>
          </ul>
          <div className="d-flex">
            {!isAuthenticated ? (
              <>
                <Link to="/login" className="btn btn-outline-primary me-2">Iniciar Sesión</Link>
                <Link to="/register" className="btn btn-primary">Registrarse</Link>
              </>
            ) : (
              <Link to="/profile" className="btn btn-outline-secondary">Mi Perfil</Link>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;