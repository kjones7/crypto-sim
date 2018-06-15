const sum1 = require('./sum');
const sum = sum1.sum;

test('adds 1 + 2 to equal 3', () => {
  expect(sum(7, 2)).toBe(9);
});