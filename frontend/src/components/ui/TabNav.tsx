interface Tab {
  key: string;
  label: string;
  icon?: React.ReactNode;
}

interface TabNavProps {
  tabs: Tab[];
  active: string;
  onChange: (key: string) => void;
}

export function TabNav({ tabs, active, onChange }: TabNavProps) {
  return (
    <div className="border-b border-gray-200 mb-6">
      <nav className="flex space-x-1 -mb-px">
        {tabs.map((t) => {
          const isActive = t.key === active;
          return (
            <button
              key={t.key}
              onClick={() => onChange(t.key)}
              className={`px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 transition-colors ${
                isActive
                  ? 'border-blue-600 text-blue-600 bg-blue-50'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'
              }`}
            >
              {t.icon && <span className="mr-2 inline-flex">{t.icon}</span>}
              {t.label}
            </button>
          );
        })}
      </nav>
    </div>
  );
}
