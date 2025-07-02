function nthWeekdayOfMonth(year: number, month: number, weekday: number, n: number): Date {
  const d = new Date(year, month - 1, 1);
  let count = 0;

  while (d.getMonth() === month - 1) {
    // Convert Python's Monday=0 to JavaScript's Sunday=0 system
    const jsWeekday = weekday === 6 ? 0 : weekday + 1;
    if (d.getDay() === jsWeekday) {
      count++;
      if (count === n) {
        return new Date(d);
      }
    }
    d.setDate(d.getDate() + 1);
  }

  throw new Error(`${year}年${month}月に${n}番目の曜日(${weekday})は存在しません。`);
}

function isHoliday(d: Date): boolean {
  const jsWeekday = d.getDay(); // Sunday=0, Monday=1, ..., Saturday=6
  const pythonWeekday = jsWeekday === 0 ? 6 : jsWeekday - 1; // Convert to Python's Monday=0, ..., Sunday=6
  const month = d.getMonth() + 1;
  const day = d.getDate();
  const year = d.getFullYear();

  // Weekend check (Saturday=5, Sunday=6 in Python's system)
  if (pythonWeekday >= 5) {
    return true;
  }

  // Fixed holidays
  if (month === 1 && day === 1) return true; // New Year's Day
  if (month === 2 && day === 23) return true; // Emperor's Birthday
  if (month === 5 && [3, 4, 5].includes(day)) return true; // Constitution Day, Greenery Day, Children's Day
  if (month === 8 && day === 11) return true; // Mountain Day
  if (month === 11 && [3, 23].includes(day)) return true; // Culture Day, Labor Thanksgiving Day

  // Variable holidays
  if (month === 1) {
    // Coming of Age Day: 2nd Monday of January
    const secondMonday = nthWeekdayOfMonth(year, 1, 0, 2);
    if (d.getTime() === secondMonday.getTime()) return true;
  }
  if (month === 7) {
    // Marine Day: 3rd Monday of July
    const thirdMonday = nthWeekdayOfMonth(year, 7, 0, 3);
    if (d.getTime() === thirdMonday.getTime()) return true;
  }
  if (month === 9) {
    // Respect for the Aged Day: 3rd Monday of September
    const thirdMonday = nthWeekdayOfMonth(year, 9, 0, 3);
    if (d.getTime() === thirdMonday.getTime()) return true;
    // Autumnal Equinox Day: simplified as September 22 or 23
    if ([22, 23].includes(day)) return true;
  }
  if (month === 10) {
    // Sports Day: 2nd Monday of October
    const secondMonday = nthWeekdayOfMonth(year, 10, 0, 2);
    if (d.getTime() === secondMonday.getTime()) return true;
  }

  return false;
}

export function calculateNewYearHoliday(year: number): [string, string, number] {
  // Basic period: December 29 of previous year to January 3 of target year
  let startDate = new Date(year - 1, 11, 29, 12, 0, 0); // Month is 0-indexed, set noon to avoid timezone issues
  let endDate = new Date(year, 0, 3, 12, 0, 0);

  // Extend backward for consecutive weekends or holidays
  let extending = true;
  while (extending) {
    const prevDay = new Date(startDate);
    prevDay.setDate(prevDay.getDate() - 1);
    if (isHoliday(prevDay)) {
      startDate = prevDay;
    } else {
      extending = false;
    }
  }

  // Extend forward for consecutive weekends or holidays
  extending = true;
  while (extending) {
    const nextDay = new Date(endDate);
    nextDay.setDate(nextDay.getDate() + 1);
    if (isHoliday(nextDay)) {
      endDate = nextDay;
    } else {
      extending = false;
    }
  }

  const holidayDays = Math.floor((endDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24)) + 1;

  // Format dates properly
  const formatDate = (d: Date): string => {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  return [
    formatDate(startDate),
    formatDate(endDate),
    holidayDays
  ];
}
