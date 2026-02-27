# � Política y Framework de Uso de Almacenamiento (WHM)

Si estás midiendo **USO % (espacio usado / cuota asignada) en WHM** —tanto para cuentas de hosting como para casillas de email— necesitas políticas claras y consistentes para:

- 📊 **Análisis**
- 👀 **Monitoreo**
- 📢 **Reportería**
- 🚨 **Escalamiento**

A continuación, se propone un framework ejecutivo + operativo que puedes implementar fácilmente en dashboards, reportes automáticos y alertas.

---

## 1️⃣ Política de Colores (Semáforo Estándar)

Se recomienda un esquema de 5 niveles, no solo 3. Esto permite anticipación real.

| Rango USO % | Color | Estado | Interpretación |
| :--- | :--- | :--- | :--- |
| **0% – 60%** | 🟢 Verde | Saludable | Sin riesgo |
| **61% – 75%** | 🟡 Amarillo | Preventivo | Vigilar crecimiento |
| **76% – 85%** | 🟠 Naranja | Advertencia | Riesgo en corto plazo |
| **86% – 95%** | 🔴 Rojo | Crítico | Acción inmediata |
| **96% – 100%+** | ⚫ Negro / 🔴 Parpadeo | Saturado | Servicio en riesgo o afectado |

**¿Por qué 60% como verde?**
En almacenamiento, después del 70% comienza a acelerarse el riesgo de crecimiento descontrolado (especialmente en email).

---

## 2️⃣ Política de Emojis (Para reportes ejecutivos y operativos)

Usa emojis con intención clara y consistente:

### Estado
- 🟢 Saludable
- 🟡 En seguimiento
- 🟠 Riesgo alto
- 🔴 Crítico
- 🚨 Acción requerida inmediata
- ⛔ Límite alcanzado
- 📈 Crecimiento acelerado
- 🧨 Riesgo de bloqueo
- 📦 Ampliación recomendada

### Para crecimiento
- ⬆️ Tendencia al alza
- ⬇️ Tendencia a la baja
- ➡️ Estable

---

## 3️⃣ Política de Recomendaciones según % de USO

### 🟢 0–60%
- Sin acción.
- Monitoreo mensual.
- Solo reporte informativo.

### 🟡 61–75%
- Revisar tasa de crecimiento mensual.
- Analizar uso histórico 90 días.
- Recomendar limpieza preventiva si es email.
- En hosting: revisar backups locales innecesarios.

### 🟠 76–85%
- Notificar al cliente.
- Analizar:
  - Crecimiento promedio mensual
  - Días estimados para llegar al 95%
- Recomendar:
  - Limpieza
  - Archivado
  - Aumento de cuota
- Monitoreo semanal.

### 🔴 86–95%
- Alerta activa.
- Evaluar impacto operativo.
- Notificación formal.
- Monitoreo diario.
- Definir:
  - Ampliación inmediata
  - Optimización urgente
- En email: riesgo de rebote inminente.

### ⚫ 96–100%+
- Acción inmediata.
- Riesgo de:
  - Rebote de correos
  - Fallas en aplicaciones
  - Errores 500
- Escalamiento técnico.
- Ampliación urgente o limpieza forzada.

---

## 4️⃣ Diferencias: Hosting vs Email

### 📧 Email
**Más crítico porque:**
- Rebota correo
- Se pierde comunicación
- Impacta ventas y operación

**Umbrales más estrictos recomendados:**
- 🔴 desde 90%
- 🚨 desde 95%

### 🌐 Hosting
**Impacto:**
- Fallas en subida de archivos
- Backups fallidos
- Errores en WordPress

*Se puede tolerar hasta 85% si crecimiento es bajo.*

---

## 5️⃣ Política de Monitoreo

### Nivel Ejecutivo (CEO / Dirección)
- **Frecuencia:** Mensual
- **Indicadores:**
  - % promedio general
  - % cuentas > 80%
  - % cuentas > 90%
  - Top 10 cuentas críticas
- **Formato recomendado:** Resumen visual + semáforo + tendencia.

### Nivel Operativo
- **Frecuencia:**
  - `> 85%` → diario
  - `75–85%` → semanal
  - `< 75%` → mensual

---

## 6️⃣ Métrica Clave que Deberías Incluir

No solo mires USO %. Agrega:

### 📈 Velocidad de crecimiento
- **Ejemplo:**
  - `+5%` mensual → bajo riesgo
  - `+15%` mensual → riesgo acelerado

### ⏳ Días estimados para saturación
- **Fórmula simple:** `(100 - % actual) / crecimiento mensual`
- *Esto cambia completamente la prioridad.*
- **Ejemplo:**
  - 82% pero crece **1%** mensual → bajo riesgo
  - 82% pero crece **12%** mensual → **crítico**

---

## 7️⃣ Formato Estándar de Reporte

**Ejemplo para dashboard:**

> **Cuenta:** clienteA.com
> **USO:** 88% 🔴
> **Tendencia:** ⬆️ +6% mensual
> **Proyección:** 2 meses para saturación
> **Recomendación:** 📦 Ampliar plan o limpiar respaldos
> **Prioridad:** Alta

---

## 8️⃣ Política de Escalamiento

| Nivel | Acción |
| :--- | :--- |
| 🟡 | Email preventivo |
| 🟠 | Email + seguimiento |
| 🔴 | Llamada + ticket |
| ⚫ | Escalamiento inmediato |

---

## 9️⃣ Buenas Prácticas Estratégicas (Nivel CEO)

Como CEO, deberías monitorear:
- % de cuentas > 85%
- % de cuentas que requieren upgrade
- Ingresos potenciales por ampliaciones
- Cuentas con mal dimensionamiento inicial

*Esto convierte monitoreo técnico en estrategia comercial.*

---

## 🔟 Política de Comunicación al Cliente

**Evitar decir:**
> ❌ “Su cuenta está casi llena”

**Mejor decir:**
> ✅ “Hemos detectado que su almacenamiento alcanza el 87%. Para evitar interrupciones en el servicio recomendamos una ampliación preventiva o limpieza.”

**Enfoque:**
- Preventivo
- Proactivo
- No alarmista
- Orientado a solución

---

## 🎯 Resumen Ejecutivo

Un buen sistema de manejo de USO % debe:
- ✔ Tener 5 niveles
- ✔ Diferenciar email de hosting
- ✔ Incluir tendencia
- ✔ Incluir proyección
- ✔ Tener política de escalamiento
- ✔ Ser accionable
