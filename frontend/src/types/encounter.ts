export interface Encounter {
  id: number;
  patient_id: number;
  encounter_type: string;
  status: string;
  chief_complaint?: string;
  created_at?: string;
  updated_at?: string;
  patient_name?: string;
}
