export default function App() {
  const api = import.meta.env.VITE_API_BASE_URL ?? '/api'

  return (
    <main style={{ fontFamily: 'system-ui', padding: 24 }}>
      <h1>Kaizen Forge</h1>
      <p>Frontend: React + Vite (TypeScript)</p>
      <p>
        API base: <code>{api}</code>
      </p>
      <p>
        Health: <a href={`${api}/health`}>{api}/health</a>
      </p>
    </main>
  )
}
