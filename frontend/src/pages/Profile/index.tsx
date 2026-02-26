import { useState, type FormEvent } from 'react';
import { useAuth } from '@/auth/useAuth';
import { changePassword, updateProfile } from '@/api/auth';

export default function Profile() {
  const { user } = useAuth();
  const [fullName, setFullName] = useState(user?.full_name ?? '');
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [profileMsg, setProfileMsg] = useState('');
  const [passwordMsg, setPasswordMsg] = useState('');
  const [profileError, setProfileError] = useState('');
  const [passwordError, setPasswordError] = useState('');

  const handleUpdateProfile = async (e: FormEvent) => {
    e.preventDefault();
    setProfileMsg('');
    setProfileError('');
    try {
      const res = await updateProfile(fullName);
      if (res.ok) {
        setProfileMsg('Profile updated successfully.');
      } else {
        setProfileError(res.error || 'Failed to update profile.');
      }
    } catch {
      setProfileError('Failed to update profile.');
    }
  };

  const handleChangePassword = async (e: FormEvent) => {
    e.preventDefault();
    setPasswordMsg('');
    setPasswordError('');
    if (newPassword !== confirmPassword) {
      setPasswordError('Passwords do not match.');
      return;
    }
    try {
      const res = await changePassword(currentPassword, newPassword);
      if (res.ok) {
        setPasswordMsg('Password changed successfully.');
        setCurrentPassword('');
        setNewPassword('');
        setConfirmPassword('');
      } else {
        setPasswordError(res.error || 'Failed to change password.');
      }
    } catch {
      setPasswordError('Failed to change password.');
    }
  };

  return (
    <div className="max-w-2xl">
      <h1 className="text-2xl font-semibold text-gray-800 mb-6">Profile</h1>

      {/* Profile Info */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 className="text-lg font-semibold text-gray-800 mb-4">Account Information</h2>
        <div className="mb-4">
          <p className="text-xs font-medium text-gray-400 uppercase">Username</p>
          <p className="text-sm text-gray-800">{user?.username}</p>
        </div>
        <div className="mb-4">
          <p className="text-xs font-medium text-gray-400 uppercase">Roles</p>
          <div className="flex flex-wrap gap-2 mt-1">
            {user?.roles.map((r, i) => (
              <span key={i} className="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">
                {r.module}{r.role ? ` — ${r.role}` : ''}
              </span>
            ))}
          </div>
        </div>

        <form onSubmit={handleUpdateProfile} className="space-y-3">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
              type="text"
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          {profileMsg && <p className="text-sm text-green-600">{profileMsg}</p>}
          {profileError && <p className="text-sm text-red-600">{profileError}</p>}
          <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
            Update Profile
          </button>
        </form>
      </div>

      {/* Change Password */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 className="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
        <form onSubmit={handleChangePassword} className="space-y-3">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input
              type="password"
              value={currentPassword}
              onChange={(e) => setCurrentPassword(e.target.value)}
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input
              type="password"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input
              type="password"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>
          {passwordMsg && <p className="text-sm text-green-600">{passwordMsg}</p>}
          {passwordError && <p className="text-sm text-red-600">{passwordError}</p>}
          <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
            Change Password
          </button>
        </form>
      </div>
    </div>
  );
}
