const sum1 = require('./sum');
const sum = sum1.sum;
const sub = sum1.sub;
test('adds 1 + 2 to equal 3', () => {
  expect(sum(1, 2)).toBe(3);
});

test('sub', () => {
    expect(sub(2, 1)).toBe(1);
  });

test('sub', () => {
  expect(sub(5, 1)).toBe(4);
});

test('sub', () => {
    expect(sub(10, 5)).toBe(5);
  });
  
test('sub', () => {
    expect(sub(111, 2)).toBe(109);
});
  
test('sub', () => {
    expect(sub(11, 2)).toBe(9);
});
