export interface Patient {
  id: number;
  patient_id?: string;
  first_name: string;
  middle_name?: string;
  last_name: string;
  suffix?: string;
  date_of_birth?: string;
  sex?: string;
  civil_status?: string;
  nationality?: string;
  religion?: string;
  blood_type?: string;
  phone?: string;
  email?: string;
  address?: string;
  city?: string;
  province?: string;
  zip_code?: string;
  emergency_contact_name?: string;
  emergency_contact_phone?: string;
  emergency_contact_relationship?: string;
  philhealth_number?: string;
  created_at?: string;
  updated_at?: string;
}
