# Temas de Investigación

## Tema 1: Estrategias de Pruebas de Regresión en Mantenimiento Evolutivo

**Enfoque:** Análisis comparativo de estrategias (completa, selectiva, basada en riesgos) y su implementación con herramientas modernas.

### Preguntas de investigación

- ¿Cómo seleccionar la estrategia de regresión óptima según el tipo de mantenimiento (correctivo, adaptativo, perfectivo)?
- ¿Qué métricas permiten evaluar la efectividad y eficiencia de cada estrategia?
- ¿Cómo integrar la regresión en un pipeline de CI/CD para reducir el tiempo de feedback?

### Componente teórico

- Fundamento de las pruebas de regresión según ISO/IEC/IEEE 14764 y SWEBOK.
- Estrategias: regresión completa, selectiva, basada en riesgos y Test Impact Analysis (TIA). En 2026, el análisis de impacto en pruebas (TIA) reduce el tiempo de ejecución de suites en un 60–80 % al analizar solo los tests afectados por los cambios.

### Componente práctico

- Seleccionar un sistema (propio o open source) y aplicar al menos dos estrategias de regresión a un cambio real o simulado.
- Implementar un conjunto de pruebas de regresión automatizadas con Playwright (estándar de facto en 2025–2026 para pruebas de interfaz) o Selenium + Pytest + Allure (para entornos Java/empresariales).
- Configurar la ejecución en un pipeline de CI/CD (GitHub Actions o Jenkins) y medir el tiempo de feedback.

### Entregables

- **Informe:** Análisis comparativo de estrategias, resultados de la aplicación práctica, métricas de efectividad (defectos de regresión detectados, tiempo de ejecución, cobertura).
- **PPT:** Presentación de 15–20 diapositivas con la justificación de la estrategia elegida y la demostración de los resultados.
- **Tutorial (aplica):** Guía paso a paso para configurar la suite de regresión automatizada y el pipeline de CI/CD.

**Herramientas clave:** Playwright, Selenium, Pytest, Allure, GitHub Actions, Jenkins.
