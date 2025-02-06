import React, { useState, useEffect } from 'react';

const ProfileView = () => {
  const [profile, setProfile] = useState(null);
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await axios.get('/api/profile');
        setProfile(response.data);
      } catch (error) {
        console.error('Error fetching profile:', error);
      }
    };

    fetchProfile();
  }, []);

  if (!profile) return (
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
          <h2 className="card-title">Mi Perfil</h2>
          <div className="row mt-3">
            <div className="col-md-6">
              <p><strong>Nombre:</strong> {profile.name}</p>
              <p><strong>Email:</strong> {profile.email}</p>
            </div>
            <div className="col-md-6 text-end">
              <button 
                className="btn btn-primary"
                onClick={() => setIsEditing(true)}
              >
                Editar Perfil
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProfileView;