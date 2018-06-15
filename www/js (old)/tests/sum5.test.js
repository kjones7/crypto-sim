const sum1 = require('./sum');
const sum = sum1.sum;

test('adds 1 + 2 to equal 3', () => {
  expect(sum(1, 4)).toBe(5);
});